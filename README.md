# File parser

This script parses a given file which contains a list of products then generates a new file with each unique set of attributes along with its number of occurrances.

### Usage

Call the script via CLI:

`php parser.php --file products_comma_separated.csv --unique-combinations combined.csv`

#### Parameters:
- `--file` - File name to parse
- `--unique-combinations` - Desired output file name