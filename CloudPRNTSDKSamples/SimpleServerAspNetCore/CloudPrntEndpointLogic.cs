using System;
using System.Collections.Generic;
using System.Data;
using System.IO;
using System.Linq;
using System.Runtime.CompilerServices;
using System.Runtime.ConstrainedExecution;
using System.Text;
using System.Text.Json;
using System.Threading.Tasks;

using Microsoft.AspNetCore.Http;

using StarMicronics.CloudPrnt;
using StarMicronics.CloudPrnt.CpMessage;

namespace SimpleServer
{
    public static class CloudPrntEndpointLogic
    {
        // Store information abbout the connected printer in local static variables - effectively only supporting a single device
        public static PrinterStatus lastStatus = null;
        public static DateTime lastUpdate;
        public static string deviceMac;
        public static int printWidth = 0;

        // information about a pending prinjob data and job format
        public static string pendingPrintJobFormat;
        public static byte[] pendingPrintJob = null;               // Put some markup data into this variable to print it

        public static async Task PrintSomething(HttpContext context)
        {
            // Prepare a markup document for printing.
            // Printing other formats can be done in the same way, by storing the raw binary data in byte[] pendingPrintJob and storing the correct
            // media type in string pendingPrintJobFormat.
             
            string markup = @"[align: centre][font: a]\
                [magnify: width 2; height 1]\
                This is a Star Markup Document!

                [magnify: width 3; height 2]Columns[magnify]
                [align: left]\
                [column: left: Item 1;      right: $10.00]
                [column: left: Item 2;      right: $9.95]
                [column: left: Item 3;      right: $103.50]

                [align: centre]\
                [barcode: type code39;
                          data 123456789012;
                          height 15mm;
                          module 0;
                          hri]
                [align]\
                Thank you for trying the new Star Document Markup Language\
                we hope you will find it useful. Please let us know!
                [cut: feed; partial]";


            // This sample only handles a single print job at a time, a production system would usually implement a job queue or generate the job
            // based on information from an order database.
            pendingPrintJobFormat = "text/vnd.star.markup";
            pendingPrintJob = Encoding.UTF8.GetBytes(markup);

            context.Response.Redirect("/");
            await context.Response.CompleteAsync();

        }

        public static async Task CloudPRNT_Poll(HttpContext context)
        {
            // Handle the cloudprnt poll. This example shows how to decode the poll request, handle some client action requests (to get the print width)
            // And generate a response with printing if there is a job pending.

            // Pre-reading the request body and then deserialising with PollRequest.FromJson()
            // helps with decoding some slightly complicated client action structures
            StreamReader reader = new StreamReader(context.Request.Body);
            string body = await reader.ReadToEndAsync();
            PollRequest cpRequest = PollRequest.FromJson(body);

            // Store the information that we want to log
            deviceMac = cpRequest.printerMAC;
            lastStatus = cpRequest.DecodedStatus;

            // Keep the connection time to calculate the time between polls
            lastUpdate = DateTime.UtcNow;

            // Check for responses to any client action responses
            if(cpRequest.clientAction != null)
            {
                foreach(ClientActionResult ca in cpRequest.clientAction)
                {
                    if (ca.Type == ClientActions.PageInfo)
                    {
                        PageInfo pi = (PageInfo)ca.GetResultObject();
                        printWidth = (int)(pi.PrintWidthMM * pi.HorizontalResolution);          // Store the device print width
                    }
                }
            }


            // Send the printer response
            PollResponse cpResponse = new PollResponse { jobReady = false };

            if(printWidth == 0)         // If we don't know the page width then request it
            {
                cpResponse.clientAction = new List<ClientActionRequest>();
                cpResponse.clientAction.Add(new ClientActionRequest(ClientActions.PageInfo, ""));
            }
            else if (pendingPrintJob != null)
            {
                cpResponse.jobReady = true;

                // If we have a job to print, then we must inform the printer which data formats we can provide it as
                cpResponse.mediaTypes = new List<string>();
                cpResponse.mediaTypes.AddRange(Document.GetOutputTypesFromType(pendingPrintJobFormat));
            }

            context.Response.ContentType = "application/json";
             
            await context.Response.WriteAsync(cpResponse.ToJson());

        }

        public static async Task CloudPRNT_FetchPrintJob(HttpContext context)
        {
            string requestedFormat = context.Request.Query["type"];
            string printerMac = context.Request.Query["mac"];           // This server is ignoring the mac, but a production system should use it to identify which printer is requesting a print job

            MemoryStream output = new MemoryStream();
            ConversionOptions opts = new ConversionOptions {
                DeviceWidth = printWidth                                // Specifying the width of the target printer ensures correct scaling and word wrapping
            };
            Document.Convert(pendingPrintJob, pendingPrintJobFormat, output, requestedFormat, opts);

            output.Seek(0, SeekOrigin.Begin);
            context.Response.ContentLength = output.Length;
            context.Response.ContentType = requestedFormat;

            await output.CopyToAsync(context.Response.Body);
        }

        public static async Task CloudPRNT_ClearJob(HttpContext context)
        {
            // clear the print job. In production please check the "code" query parameter to ensure that the request is iin response to a successful print or failure
            pendingPrintJob = null;

            await context.Response.CompleteAsync();
        }
    }
}
