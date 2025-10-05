# CEIDG API Client

This is a client library for accessing the CEIDG API (Central Registration and Information on Business).

## Installation

To install the CEIDG API Client, use npm:

<!-- ```bash
npm install ceidg-api-client
``` -->

## Usage

```javascript
const { CeidgApiClient } = require('ceidg-api-client');

const client = new CeidgApiClient({
  apiKey: 'YOUR_API_KEY',
  apiSecret: 'YOUR_API_SECRET',
});

// Example: Get information about a specific business
client.getBusinessInfo('1234567890')
  .then(info => {
    console.log('Business Information:', info);
  })
  .catch(error => {
    console.error('Error fetching business information:', error);
  });
```

## API Reference

### `CeidgApiClient`

#### `constructor(options)`

Creates a new instance of the CEIDG API Client.

- `options` (Object): Configuration options for the client.
  - `apiKey` (String): Your API key.
  - `apiSecret` (String): Your API secret.

#### `getBusinessInfo(sid)`

Fetches information about a specific business.

- `sid` (String): The unique identifier of the business.

Returns a promise that resolves to the business information.

## Contributing

Contributions are welcome! Please submit a pull request or open an issue for any enhancements or bug fixes.

## License

This project is licensed under the MIT License.