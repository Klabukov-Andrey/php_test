# php_test
curl тест
curl -H 'Content-Type:application/json' -d '{"product":1,"taxNumber":"GR123456789","couponCode":"D15"}' X POST http://localhost/api/v1/calculate-price
curl -H 'Content-Type:application/json' -d'{"product":1,"taxNumber":"GR123456789","couponCode":"P5"}' X POST http://localhost/api/v1/calculate-price
curl -H 'Content-Type:application/json' -d'{"product":1,"taxNumber":"GR123456789"}' X POST http://localhost/api/v1/calculate-price
curl -H 'Content-Type:application/json' -d'{"product":1,"taxNumber":"GR123456789","couponCode":"D15","paymentProcessor":"paypal"}' X POST http://localhost/api/v1/process-payment
