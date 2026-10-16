# Exercise 2 – Car Spare Parts HTTP API

## Project Overview

This project was developed as part of the Distributed Applications course (*Hajusrakendused*).

The goal is to build a PHP-based HTTP API that reads car spare parts data from a CSV/TSV file and allows users to search for products through HTTP requests.

The dataset contains **108,866 spare parts** exported from a legacy inventory management system.

**Original assignment:** [GitHub – spareparts.md](https://github.com/timotr/harjutused/blob/main/hajusrakendused/spareparts.md)

## Technologies Used

- PHP
- HTTP GET requests
- JSON
- CSV/TSV file processing
- Git and GitHub
- Visual Studio Code

## Features

- Read and parse spare parts data from a TSV file
- Convert data into JSON format
- Search spare parts by product code
- Search spare parts by product name
- Combine multiple search parameters
- Pagination with 30 products per page
- Convert legacy Windows-1257 encoded data into UTF-8
- Handle invalid API endpoints with HTTP 404 responses

## Project Structure

```text
harjutus2/
├── index.php           # HTTP API
├── convert.php         # File encoding conversion
├── README.md           # Project documentation
└── LE/
    ├── LE.txt          # Original dataset
    └── LE_utf8.txt     # Converted UTF-8 dataset
```

The data files are stored locally and are not included in the GitHub repository.

## Installation and Setup

### 1. Clone the repository

```bash
git clone https://github.com/murakas82/hajusrakendused.git
cd hajusrakendused/harjutus2
```

### 2. Download the dataset

Download [LE.txt](https://github.com/timotr/harjutused/blob/main/hajusrakendused/LE.txt) and save it inside the `LE` directory.

### 3. Convert the file encoding

Run:

```bash
php convert.php
```

This creates `LE_utf8.txt`, which is used by the API.

### 4. Start the PHP server

```bash
php -S localhost:3300 index.php
```

### 5. Access the API

Open:

http://localhost:3300/spare-parts

## API Endpoints

| Endpoint | Description |
|---|---|
| `/spare-parts` | Returns the first 30 products |
| `/spare-parts?page=2` | Returns the second page |
| `/spare-parts?name=BMW` | Searches products by name |
| `/spare-parts?sn=00013601579` | Searches by product code |
| `/spare-parts?name=BMW&page=2` | Paginated search results |
| `/spare-parts?name=BMW&sn=00013601579` | Combined search |

### Example JSON Response

```json
[
    {
        "code": "00013601579",
        "name": "BMW Raamat",
        "price": "0"
    }
]
```

## Data Processing

The original dataset contains 108,866 rows with 11 columns each.

The file uses tab-separated values and contains text with mixed encodings, primarily Windows-1257.

The `convert.php` script processes product names and generates a UTF-8 compatible file.

**Conversion results:**

- Total rows processed: 108,866
- All 11 columns preserved per row
- Invalid UTF-8 product names after conversion: 0
- One product name required removing an invalid character

The API currently uses three fields: product code, product name, and a preliminary price field whose exact meaning has not yet been confirmed.

## Known Limitations and Future Improvements

- Price sorting is not yet implemented.
- Prices are currently stored as strings using decimal commas.
- The PHP built-in server reloads the dataset on every HTTP request.
- A persistent server is needed to load the dataset into memory once.
- Automated daily data import and conversion are not yet implemented.

## Author

**Ilmar Murakas**  
Software Development Student – KTA-25

