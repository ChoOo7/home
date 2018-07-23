import logging
import collections
import time
from pydispatch import dispatcher
from collections import OrderedDict
logging.basicConfig()
logging.root.setLevel(logging.WARNING)

import zigate
z = zigate.ZiGate(port=None) # Leave None to auto-discover the port

def testinterpretResponse(sender, signal, **kwargs):
  global count;
  #print('CUSTOM response received');
# RESPONSE 0x8102 - Individual Attribute Report : sequence:150, addr:f659, endpoint:1, cluster:6, attribute:0, status:0, data_type:16, size:1, data:True, rssi:111
  print(sender)  # zigate instance
  print(signal)  # one of EVENT
  print(kwargs)  # contains device and/or attribute changes, etc
  #print(response);


#z.action_onoff('b89c', 11, 0);
#z.action_onoff('b89c', 11, zigate.OFF);

#dispatcher.connect(testinterpretResponse, 'ZIGATE_RESPONSE_RECEIVED')
dispatcher.connect(testinterpretResponse, 'ZIGATE_ATTRIBUTE_ADDED')
dispatcher.connect(testinterpretResponse, 'ZIGATE_ATTRIBUTE_UPDATED')



for x in range(20):
  time.sleep(3)
  print('sleep');

  #led.action_onoff(2);
  #print(x%2);
  #z.action_onoff('b89c', 11, x%2);

print('fin');