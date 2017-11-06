var express = require('express');
var app = express.Router();

var data = {}

data.stockSeccess = {
  "success": true,
  "error": "",
  "data": [
    {
      "message": "3-5 days to arrive",
      "status": "Continue through the checkout process to make a booking and your selected store will ensure stock is available at your booking time.",
      "sku": "567393",
      "item": "165989",
      "qty": "2",
      "sapcode": "567393"
    }
  ],
  "publicHoliday": []
}

app.route('/checkStock').get(function(req,res){
  console.log('Checking stock');
  res.send(data.stockSeccess);
});

module.exports = app;
