const http = require('http'),
  hostName = '127.0.0.1',
  port = 3000;

http.createServer((req, res) => {
  res.statusCode = 200;
  res.setHeader('Content-Type:', 'text/plain');
  res.end("Hallo Gaes !!!");

}).listen(port, hostName, () => {
  console.log('Server is Running');
});