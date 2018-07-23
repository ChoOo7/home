import logging
import collections
import time
from pydispatch import dispatcher
from collections import OrderedDict
logging.basicConfig()
logging.root.setLevel(logging.WARNING)

import zigate
z = zigate.ZiGate(port=None) # Leave None to auto-discover the port

#print(z.get_version())
#OrderedDict([('major', 1), ('installer', '30c'), ('rssi', 0), ('version', '3.0c')])

#print(z.get_version_text())
#3.0c

# refresh devices list
#z.get_devices_list()
#z.need_refresh();
print(z.devices)

z.action_onoff('b89c', 11, 2);

print('fin');