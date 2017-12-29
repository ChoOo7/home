
String.prototype.reverse=function(){return this.split("").reverse().join("");}
function getRandomColor(deviceName) {
  var theHash = md5(deviceName+"qsd").substr(1,5);
  var randomNumber = parseInt(theHash, "36") * 17;
  var letters = '0123456789ABCDEF';
  var color = '#';
  for (var i = 0; i < 3; i++) {

    var num = (i * (i + randomNumber)) % 16;
    var part1 = randomNumber % 16;
    var part2 = ( (randomNumber + 8) % (i+7) ) % 16;
    num = (part1 + part2) % 16;
    //color += letters[Math.floor(Math.random() * 16)];
    color += letters[num]+letters[num];
  }
  return color;
}