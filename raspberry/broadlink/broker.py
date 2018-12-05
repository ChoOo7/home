#

import logging
import paho.mqtt.client as mqtt
import json
import broadlink
import time



class MQTT_Broker(object):
    def __init__(self, mqtt_host='localhost:1883', username=None, password=None):
        self._mqtt_host = mqtt_host
        self.client = mqtt.Client(None, True)
        if username is not None:
            self.client.username_pw_set(username, password)
        self.client.on_connect = self.on_connect
        self.client.on_message = self.on_message

    def connect(self):
        host, port = self._mqtt_host.split(':')
        port = int(port)
        print("connecting")
        print(self.client.connect(host, port))

    def start(self):
        self.connect()
        print("starting")
        self.devices = broadlink.discover(timeout=5)
        print("discovered")
        self.devices[0].auth()
        print("authed")

        run = True
        while run:
            self.client.loop()


    def _publish(self, topic, payload=None):
        if payload:
            payload = json.dumps(payload, cls=DeviceEncoder)
        logging.info('Publish {}'.format(topic))
        self.client.publish(topic, payload, retain=True)

    def state_changed(self, deviceIndex):
        logging.debug('state_changed {}'.format(deviceIndex))
        self._publish('broadlink/state_changed/{}'.format(deviceIndex))

    def on_connect(self, client, userdata, flags, rc):
        logging.info("MQTT connected with result code {}".format(rc))
        print("MQTT connected with result code {}".format(rc))
        client.subscribe("broadlink/command/#")
        print("suscribed")

    def on_message(self, client, userdata, msg):
        payload = {}
        if msg.payload:
            payload = json.loads(msg.payload.decode())
        if msg.topic == 'broadlink/command':
            deviceIndex = payload.get('deviceIndex')
            deviceState = payload.get('deviceState', [])

            self.devices[deviceIndex].set_power(deviceState);



if __name__ == '__main__':
    logging.basicConfig()
    logging.root.setLevel(logging.INFO)
    import argparse

    parser = argparse.ArgumentParser()
    parser.add_argument('--mqtt_host', help='MQTT host:port', default='localhost:1883')
    parser.add_argument('--mqtt_username', help='MQTT username', default=None)
    parser.add_argument('--mqtt_password', help='MQTT password', default=None)
    args = parser.parse_args()

    broker = MQTT_Broker(args.mqtt_host, args.mqtt_username, args.mqtt_password)
    broker.start()
