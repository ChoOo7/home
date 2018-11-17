const DashButton = require('dash-button');

var exec = require('child_process').exec;


const DASH_BUTTON_MAC_ADDRESS_CASTOR = '6c:56:97:7e:d3:d6';
const DASH_BUTTON_MAC_ADDRESS_INTER = '6c:56:97:dd:ca:b1';

let buttonCastor = new DashButton(DASH_BUTTON_MAC_ADDRESS_CASTOR);
let buttonInter = new DashButton(DASH_BUTTON_MAC_ADDRESS_INTER);

let subscriptionCastor = buttonCastor.addListener(async () => {
    console.log('Bouton castor');
/*
    exec('pwd', function callback(error, stdout, stderr){
    // result
      console.log('stdout');
      console.log(stdout);
      console.log('fin');
    });
*/
  exec('php /var/home/raspberry/homecmd.php castor', function callback(error, stdout, stderr){
    // result
    console.log('stdout');
    console.log(stdout);
    console.log('fin');
  });
});
let subscriptionInter = buttonInter.addListener(async () => {
    console.log('Bouton inter');

  exec('php /var/home/raspberry/homecmd.php cuisineinter', function callback(error, stdout, stderr){
    // result
    console.log('stdout');
    console.log(stdout);
    console.log('fin');
  });
});

console.log('Inited');