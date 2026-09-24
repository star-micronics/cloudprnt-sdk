- [日本語](Documents/CHANGELOG_JP.md)

# Change Log

## 2.1.0 (2026/9/15)

- Support exit codes.
  - 0 : Success
  - 1 : Runtime error
  - 2 : Usage error

## 2.0.1 (2025/8/27)

- Update due to bug fixes in the dependency package (StarDocumentMarkup-Utility).
  - Fixed an issue where an error would occur when specifying a JPEG image as the source data.

## 2.0.0 (2025/5/19)

- Support the following command option.
  - 300dpi
  - text-mag-1_5x
- Bug Fix
  - Fixed a problem that could cause unintended printing results when an image containing
    transparency information is input.

## 1.2.0 (2025/3/10)

- Support the command option of `-template` for `decode` command : for template printing function.
- Update the SixLabors.ImageSharp library from V1.0.4 to V2.1.9.
- Update the .NET framework from .NET 6.0 to .NET 8.0.
- Support the following Star Document Markup tags.
  - `[buzzer]`
  - `[drawer]`
  - `[fixedWidth]`
  - `[linespacing]`
  - `[templateArray]`
- Support the `variable-left` option of `[column]` Star Document Markup tag.

## 1.1.2 (2022/4/28)

- Update the SixLabors.ImageSharp library from V1.0.2 to V1.0.4 to support .NET 6.0 environment.
- Update the Newtonsoft.Json library from V12.0.3 to V13.0.1.
- Update the .NET framework from .NET Core 3.1 to .NET 6.0.

## 1.1.1 (2021/1/18)

- Update the SixLabors.ImageSharp library from V100-beta007 to V1.0.2.
- Update the .NET Core framework from 2.1 to 3.1.
- Improve the long data URLs length limit in Markup.
- Support for the sbcs option in cputil for models that do not support utf8 code pages.

## 1.1.0 (2020/6/17)

- Support the command option of drawer for controlling CashDrawer.
- Support the command option of buzzer for controlling Buzzer.
- Support embedded data urls for `[image]` markup tag.
- Support `[negative]` markup tag to printing colour inverted text.
- Support `[invert]` markup tag to support upside-down printing.
- Support the command options of holdprint and presentstatus for TSP650IISK model.

## 1.0.0 (2019/11/5)

- First release.
