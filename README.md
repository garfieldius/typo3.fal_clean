# FAL Cleanup

This is a TYPO3 CMS extension that provides a CLI command for removing all unused files from the *fileadmin* and *uploads* directory using the reference table `sys_file_reference` of FAL.

## Installation

Add it into the `typo3conf/ext` folder.

via git
```bash
git clone https://github.com/trenker/typo3.fal_clean.git typo3conf/ext/fal_clean
```

or via composer
```bash
composer require georggrossberger/fal-clean
```

Using the composer method will, of course, require an installation using the [composer integration](http://composer.typo3.org) of TYPO3.

In both cases, go to the extension manager module and install it.

There is currently no TER release.

## Usage

The extension offers two CLI extbase commands: `simulate` and `execute`. The first will list which records and files will be deleted. The second one acutally deletes them.

```bash
# Show unused files and records
php typo3/cli_dispatch.phpsh extbase falclean:simulate

# Remove the files
php typo3/cli_dispatch.phpsh extbase falclean:simulate
```

Make sure your FAL relations are properly set up, or it will delete more than desired.

## License

[The MIT License (MIT)](http://opensource.org/licenses/MIT)

Copyright (c) 2015 Georg Großberger

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
