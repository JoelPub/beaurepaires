var express = require('express')
var proxy = require('express-http-proxy');
var setup = require('./setup.js');
var app = express()

// global controller
app.get('/*',function(req,res,next){
    res.setHeader('Access-Control-Allow-Origin', setup.devServer);
    next();
});

// A friendly hello :)
app.get('/hello', function (req, res) {
  console.log('Saying Hello');
  res.send('Hello World');
})

// run all proxys through here (setup.proxyHost)
// Example:
// http://localhost:8082/proxy/searchautocomplete/ajax/get/?q=goodyear
app.use('/proxy', proxy(setup.proxyHost, {
  proxyReqOptDecorator: function(proxyReqOpts, srcReq) {
    console.log('Running proxys from ' + setup.proxyHost);
    return proxyReqOpts;
  }
}));

app.use('/stock', require('./requests/getStock.js'));

app.listen(setup.port, function () {
  console.log('Running server on port '+ setup.port)
})
