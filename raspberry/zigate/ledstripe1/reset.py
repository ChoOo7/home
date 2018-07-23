import logging
import collections
import time
from pydispatch import dispatcher
from collections import OrderedDict
logging.basicConfig()
logging.root.setLevel(logging.WARNING)

import zigate
z = zigate.ZiGate(port=None) # Leave None to auto-discover the port

# refresh devices list

z.get_devices_list()
print(z.devices)
print('endpoints')
print(z.devices[0].endpoints)
#print(z.devices[1].endpoints)

z.reset()
z.permit_join()

time.sleep(30);