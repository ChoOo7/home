import logging
import collections
import time
from pydispatch import dispatcher
from collections import OrderedDict
logging.basicConfig()
logging.root.setLevel(logging.WARNING)

import zigate
z = zigate.ZiGate(port=None) # Leave None to auto-discover the port

#z.reset()
z.permit_join()
time.sleep(30);
z.permit_join()
time.sleep(30);
z.permit_join()
time.sleep(30);
z.permit_join()
time.sleep(30);
z.permit_join()
time.sleep(30);
z.permit_join()
time.sleep(30);
z.permit_join()
time.sleep(30);
z.permit_join()
time.sleep(30);
/*


RESPONSE 0x8102 - Individual Attribute Report : sequence:0, addr:ee65, endpoint:3, cluster:6, attribute:0, status:0, data_type:16, size:1, data:True, rssi:66
RESPONSE 0x8102 - Individual Attribute Report : sequence:1, addr:ee65, endpoint:3, cluster:8, attribute:0, status:0, data_type:32, size:1, data:254, rssi:63
RESPONSE 0x8102 - Individual Attribute Report : sequence:2, addr:ee65, endpoint:3, cluster:768, attribute:0, status:0, data_type:32, size:1, data:0, rssi:69
RESPONSE 0x8102 - Individual Attribute Report : sequence:3, addr:ee65, endpoint:3, cluster:8, attribute:0, status:0, data_type:32, size:1, data:254, rssi:69
RESPONSE 0x8102 - Individual Attribute Report : sequence:4, addr:ee65, endpoint:3, cluster:768, attribute:1, status:0, data_type:32, size:1, data:0, rssi:66
*/