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

print('properties')
print(z.devices[0].properties)
#print(z.devices[1].properties)

#z.devices[0].refresh_device()
#z.devices[1].refresh_device()

print('view group')
print(z.view_group('fd1d', 1, 'e710'))
print(z.view_group('b89c', 11, 'e710'))

print('associating')
time.sleep(3)
print(z.groups)
# telecommande
print(z.add_group_identify('fd1d', 1, 'e710'))
print(z.groups)

# led stripe
print(z.add_group_identify('b89c', 11, 'e710'))
print(z.groups)



time.sleep(3)

print('identifying')
print(z.identify_device('fd1d'))

time.sleep(1)

print(z.identify_device('b89c'))

#z.save_state()

time.sleep(3)

print('fin');
#ZIGATE_ATTRIBUTE_ADDED
#{'attribute': {'endpoint': 1, 'value': False, 'attribute': 0, 'addr': 'f659', 'data': False, 'cluster': 6, 'name': 'onoff'}, 'zigate': <zigate.core.ZiGate object at 0x76a4d4d0>, 'device': Device f659 }
#<zigate.core.ZiGate object at 0x76a4d4d0>
#
#b'\x01\x81\x02\x12\x02\x10\x02\x1e\xc8\x93\xf6Y\x02\x11\x02\x10\x02\x16\x02\x10\x02\x10\x02\x10\x10\x02\x10\x02\x11\x02\x10o\x03'
#bytearray(b'\x81\x02\x00\x0e<\x94\xf6Y\x01\x00\x06\x00\x00\x00\x10\x00\x01\x00\x9c')
#ZIGATE_ATTRIBUTE_UPDATED

#e710
#{'e710': {('f659', 11)}}