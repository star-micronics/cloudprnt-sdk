using System;
using System.IO;
using System.Reflection;
using System.Collections.Generic;
using StarMicronics.CloudPrnt;

using Newtonsoft.Json;

namespace cputil
{
    class Program
    {
        static ConversionOptions opts = new ConversionOptions();
        
        static void Main(string[] args)
        {
            opts.JobEndCutType = CutType.Full;
            opts.JobEndFeedToCutter = true;

            for (int i = 0; i < args.Length; i++)
            {
                switch (args[i].ToLower())
                {
                    case "help":
                        PrintHelp();
                        break;

                    case "version":
                    case "--version":           // Common practive to support this flag on Linux
                        DisplayInfo();
                        break;

                    case "jsonstatus":
                        Console.WriteLine(ConvertStatusToJson(args[++i]));
                        break;

                    case "mediatypes":
                        {
                            string[] outputList = Document.GetOutputTypesFromFileName(args[++i]);
                            Console.WriteLine(JsonConvert.SerializeObject(outputList));
                        }
                        break;

                    case "mediatypes-mime":
                        {
                            string[] outputList = Document.GetOutputTypesFromType(args[++i]);
                            Console.WriteLine(JsonConvert.SerializeObject(outputList));
                        }
                        break;

                    case "supportedinputs":
                        PrintInputs();
                        break;

                    case "decode":
                        if(args.Length - i < 3)
                        {
                            PrintHelp();
                            break;
                        }

                        string format = args[++i];
                        string filename = args[++i];
                        string outputfile = args[++i];

                        Stream s = null;

                        if (outputfile == "-" || outputfile == "" || outputfile == "[stdout]")
                            s = Console.OpenStandardOutput();
                        else
                            s = new FileStream(outputfile, FileMode.Create);

                        //decode(format, filename, s);
                        Document.ConvertFile(filename, s, format, opts);
                        s.Close();

                        Console.Error.WriteLine(String.Format("Wrote output to \"{0}\"", outputfile));
                        break;

                    case "printarea":
                        if(args.Length - i < 1)
                        {
                            PrintHelp();
                            break;
                        }
                        int printableArea = 0;
                        string printarea_length = args[++i];
                        bool result = int.TryParse(printarea_length, out printableArea);

                        if (result)
                            opts.DeviceWidth = printableArea;
                        else
                            Console.Error.WriteLine("Input value is incorrect. Please set correct value. e.g.) \"printarea XXX(X is numeric)\" ");

                        break;

                    case "matrix57.5":
                        opts.DeviceWidth = 160;
                        break;

                    case "matrix69.5":
                        opts.DeviceWidth = 190;
                        break;

                    case "matrix3":
                    case "matrix76":
                        opts.DeviceWidth = 210;
                        break;

                    case "thermal2":
                    case "thermal58":
                        opts.DeviceWidth = 48 * 8;
                        break;

                    case "thermal3":
                    case "thermal80":
                        opts.DeviceWidth = 576;
                        break;

                    case "thermal82":
                    case "thermal83":
                        opts.DeviceWidth = 640;
                        break;

                    case "thermal4":
                    case "thermal112":
                        opts.DeviceWidth = 832;
                        break;

                    case "utf8":
                        opts.SupportUTF8 = true;
                        break;

                    case "scale-to-fit":
                        opts.ScaleToFit = true;
                        break;

                    case "dither":
                        opts.PerformDither = true;
                        break;

                    case "nodither":
                        opts.PerformDither = false;
                        break;

                    case "fullcut":
                        opts.JobEndCutType = CutType.Full;
                        break;

                    case "partialcut":
                        opts.JobEndCutType = CutType.Partial;
                        break;

                    case "waitkey":
                        Console.ReadKey();
                        break;
                }
            }

            if (args.Length == 0)
                PrintHelp();             
             
        }

        static void PrintHelp()
        {
            String help = String.Join(Environment.NewLine,
                "cputil - Star CloudPRNT helper utility",
                "======================================",
                "",
                "Options:",
                "  help                                - display this help message.",
                "  version                             - display version information.",
                "  supportedinputs                     - list supported input data formats, as a JSON array.",
                "  jsonstatus <ASB status>             - Convert cloudPRNT reported ASB status into JSON",
                "  mediatypes <filename>               - Test the specified file and report which media",
                "                                        types can be decoded to as a JSON array.",
                "  mediatypes-mime <media type>        - Report which output media formats are supported",
                "                                        for conversion from a specified input media type.",
                "                                        <media type> should be specified as an IANA/MIME",
                "                                        formatted media content type string, for example",
                "                                        \"image/png\" or \"application/vnd.star.markup\".",
                "  decode <format> <filename> <output> - Convert file to the specified format. Format should",
                "                                        be provides as a media type string.",
                "                                        decoder data is writtenn to the file specified by",
                "                                        <output>. If output is set to \"-\" or \"[stdout]\"",
                "                                        then data will be written to standard output.",
                "  thermal2/thermal58                  - set device constraints for a thermal 58mm/2\" printer",
                "  thermal3/thermal80                  - set device constraints for a thermal 80mm/3\" printer",
                "  thermal82/thermal83                 - set device constraints for a thermal 82mm/83mm printer",
                "  thermal4/thermal112                 - set device constraints for a thermal 112mm/4\" printer",
                "  matrix57.5                          - set device constraints for a dot-matrix 57.5mm\" printer",
                "  matrix69.5                          - set device constraints for a dot-matrix 69.5mm\" printer",
                "  matrix3/matrix76                    - set device constraints for a dot-matrix 76mm/3\" printer",
                "  printarea <dot length>              - set device constraints for a specified printable area dot size of printer",
                "  utf8                                - specify that the target device supports UTF8 encoding",
                "  dither                              - specify that colour/greyscale images should be ditherer",
                "  scale-to-fit                        - specify that any images which exceed the device width",
                "                                        should be resized to fit the page.",
                "  fullcut                             - request a full cut at the end of the print job, only if",
                "                                        the input job format does not specify a cut method.",
                "  partialcut                          - request a partial  cut at the end of the print job, only",
                "                                        if the input job format does not specify a cut method.",
                "  waitkey                             - wait for a key press, use as last parameter when",
                "                                        it is useful to see the output of other options.");

            Console.WriteLine(help);
        }

        static void DisplayInfo()
        {
            Assembly a = typeof(StarMicronics.CloudPrnt.Document).Assembly;
            AssemblyName n = a.GetName();
            Version v = n.Version;

            Console.WriteLine("{0}: {1}.{2}.{3}.{4}", n.Name, v.Major, v.Minor, v.Build, v.Revision);
            Console.WriteLine("cputil: 1.0.0.0");
        }

        static void PrintInputs()
        {
            string[] inputs = new string[]
            {
                "text/plain",
                "text/vnd.star.markup",
                "image/png",
                "image/jpeg",
                "image/bmp",
                "image/gif"
            };
            Console.WriteLine(JsonConvert.SerializeObject(inputs, Formatting.Indented));
        }

        static string ConvertStatusToJson(string status)
        {
            PrinterStatus stat = new PrinterStatus(status);

            return JsonConvert.SerializeObject(stat, Formatting.Indented);
        }

        static string GetOutputMediatTypes(string filename)
        {
            return JsonConvert.SerializeObject(Document.GetOutputTypesFromFileName(filename));
        }

    }
}
