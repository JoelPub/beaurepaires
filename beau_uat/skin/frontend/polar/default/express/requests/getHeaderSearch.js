var express = require('express')
var proxy = require('express-http-proxy');
var setup = require('../setup.js');

// app.use('/proxy', proxy(proxyHost, {
//   proxyReqOptDecorator: function(proxyReqOpts, srcReq) {
//     return proxyReqOpts;
//   }
// }));
//
//
// me.route('/').get(function(req,res){
//     res.send("Welcome to Me");
// });
//
// module.exports = getHeaderSearch;

module.exports = function(app)
{
  // app.use('/proxy', proxy(setup.proxyHost, function() {
  //   console.log('test');
  // }));
  app.use('/getHeaderSearch', proxy(setup.proxyHost + '/searchautocomplete/ajax/get/', {
    proxyReqOptDecorator: function(proxyReqOpts, srcReq) {
      console.log('Requesting getHeaderSearch (proxy)');
      return proxyReqOpts;
    }
  }));
}
