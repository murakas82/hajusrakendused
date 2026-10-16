# Harjutus 1 – Tallinn Weather Forecast

Simple PHP application that fetches Tallinn weather data from the MET Norway / Yr.no API.

## Features

- Sends an HTTP GET request to the weather API
- Uses Tallinn latitude and longitude
- Reads JSON response data
- Shows the nearest forecast
- Shows the forecast 3 hours later
- Uses a `for` loop
- Converts UTC time to Tallinn local time

## Tallinn coordinates

```text
Latitude: 59.4370
Longitude: 24.7536
```

## API

MET Norway Locationforecast API:

```text
https://api.met.no/weatherapi/locationforecast/2.0/compact
```

## Example output

```text
18:00 local → 13.6C
21:00 local → 10.8C
```

## Run

```powershell
php index.php
```

## Code explanation

### 1. Start PHP

```php
<?php
```

This tells the file that PHP code starts here.

---

### 2. Store Tallinn coordinates

```php
$latitude = 59.4370;
$longitude = 24.7536;
```

These are variables.

In PHP, variables start with:

```text
$
```

So:

```php
$latitude
```

means a variable called `latitude`.

The values are Tallinn's geographical coordinates.

---

### 3. Create the API URL

```php
$url = "https://api.met.no/weatherapi/locationforecast/2.0/compact?lat=$latitude&lon=$longitude";
```

This creates the address where the weather request will be sent.

The coordinates are added as query parameters:

```text
?lat=59.4370&lon=24.7536
```

PHP automatically replaces:

```php
$latitude
```

and:

```php
$longitude
```

with their values.

---

### 4. Create HTTP settings

```php
$options = [
    "http" => [
        "header" => "User-Agent: TallinnWeatherExercise/1.0\r\n"
    ]
];
```

This creates settings for the HTTP request.

The `User-Agent` tells the API which application is making the request.

The structure is an associative array:

```text
$options
└── http
    └── header
```

---

### 5. Prepare the HTTP context

```php
$context = stream_context_create($options);
```

This takes the settings from `$options` and prepares them for the HTTP request.

So:

```text
$options
↓
stream_context_create()
↓
$context
```

---

### 6. Send the HTTP request

```php
$response = file_get_contents($url, false, $context);
```

This is the line that actually contacts the weather API.

It uses:

```php
$url
```

to know where to send the request.

It uses:

```php
$context
```

for the HTTP settings.

The response from the API is stored in:

```php
$response
```

The response is JSON text.

---

### 7. Convert JSON into a PHP array

```php
$data = json_decode($response, true);
```

The API sends JSON.

PHP converts the JSON into an associative array.

The result is stored in:

```php
$data
```

The `true` tells PHP to create arrays instead of objects.

---

### 8. Start the loop

```php
for ($i = 0; $i <= 3; $i += 3) {
```

This loop runs twice.

The values of `$i` are:

```text
0
3
```

Why?

The loop starts here:

```php
$i = 0
```

Then it continues while:

```php
$i <= 3
```

After every loop, this happens:

```php
$i += 3
```

which means:

```text
add 3 to $i
```

So the loop reads:

```text
forecast 0
forecast 3
```

This gives the nearest forecast and the forecast around 3 hours later.

---

### 9. Get the forecast time

```php
$time = $data["properties"]["timeseries"][$i]["time"];
```

The weather data is stored deep inside the JSON structure.

The path is:

```text
data
└── properties
    └── timeseries
        └── forecast item
            └── time
```

`$i` tells PHP which forecast item to use.

First:

```text
$i = 0
```

Then:

```text
$i = 3
```

---

### 10. Get the temperature

```php
$temperature = $data["properties"]["timeseries"][$i]["data"]["instant"]["details"]["air_temperature"];
```

The temperature is stored deeper inside the structure.

The path is:

```text
data
└── properties
    └── timeseries
        └── forecast item
            └── data
                └── instant
                    └── details
                        └── air_temperature
```

The value is stored in:

```php
$temperature
```

---

### 11. Create a DateTime object

```php
$dateTime = new DateTime($time);
```

The API gives the time in UTC format.

Example:

```text
2026-10-02T15:00:00Z
```

This line converts that text into a PHP `DateTime` object.

---

### 12. Convert UTC time to Tallinn time

```php
$dateTime->setTimezone(new DateTimeZone("Europe/Tallinn"));
```

This converts the API time to local Tallinn time.

For example:

```text
15:00 UTC
```

can become:

```text
18:00 Tallinn time
```

depending on daylight saving time.

Using:

```text
Europe/Tallinn
```

is better than manually adding hours, because PHP handles summer and winter time automatically.

---

### 13. Print the result

```php
echo $dateTime->format("H:i") . " local → " . $temperature . "C" . PHP_EOL;
```

This prints the final result.

`format("H:i")` means:

```text
hours:minutes
```

For example:

```text
18:00
```

The `.` joins text and variables together.

So this:

```php
$dateTime->format("H:i") . " local → " . $temperature . "C"
```

can become:

```text
18:00 local → 13.6C
```

`PHP_EOL` creates a new line.

---

## Program flow

The whole program works like this:

```text
Tallinn coordinates
        ↓
Create API URL
        ↓
Create HTTP settings
        ↓
Send HTTP request
        ↓
Receive JSON
        ↓
Convert JSON to PHP array
        ↓
Use loop
        ↓
Read forecast time
        ↓
Read temperature
        ↓
Convert UTC to Tallinn local time
        ↓
Print result
```

## Technologies

- PHP
- JSON
- HTTP
- MET Norway Weather API
- Visual Studio Code
