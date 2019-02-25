<template>
  <div class="home">
    <div class="loadingZone">
      <i class="el-icon-loading" v-if="loadingCounter"></i>
    </div>
    <el-row>
      <el-col :span="24">
        <el-tabs type="border-card">
          <el-tab-pane label="Ampli">
            <el-button-group>
              <el-button type="primary" icon="el-icon-error" class="launchAction" v-on:click.stop.prevent="launchAction" data-do="off">Power Off</el-button>
            </el-button-group>
            <el-button-group>
              <el-button icon="el-icon-minus" class="launchAction" v-on:click.stop.prevent="launchAction" data-do="volumeDown"></el-button>
              <el-button icon="el-icon-plus" class="launchAction" v-on:click.stop.prevent="launchAction" data-do="volumeUp"></el-button>
            </el-button-group>
            <br />
            <h3>Temperature : {{ sensors.temperature }}</h3>
            <br />
            <div v-if="facts && facts.presence_detector">
              <h3>Last movement : {{ facts.presence_detector.last_action }}</h3>
              <br />
            </div>
            <div v-if="facts && facts.tel_sim_connect">
              <h3>Tel sim last co : {{ facts.tel_sim_connect.last_action }}</h3>
              <br />
            </div>
            <div v-if="facts && facts.tel_so_connect">
              <h3>Tel sim last co : {{ facts.tel_so_connect.last_action }}</h3>
              <br />
            </div>
            <el-button-group>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="castor">Pere Castor</el-button>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="pig">Peppa pig</el-button>
            </el-button-group>
            <br />
            <el-button-group>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="ikeaLamp1On">Lamp ON</el-button>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="ikeaLamp1High">High</el-button>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="ikeaLamp1Medium">Medium</el-button>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="ikeaLamp1Low">Low</el-button>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="ikeaLamp1Off">Lamp OFF</el-button>
            </el-button-group>
            <el-slider v-model="ikea1LampIntensity"></el-slider>

          </el-tab-pane>

          <el-tab-pane label="Radio">

            <el-button-group>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="inter">France Inter</el-button>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="fip">FIP</el-button>
            </el-button-group>

            <br />

            <el-button-group>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="meuh">Radio MEUH</el-button>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="rtu">RTU</el-button>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="perfecto">Radio Perfecto</el-button>
            </el-button-group>

            <br />

            <el-button-group>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="chantefrance">Chantefrance</el-button>
            </el-button-group>

          </el-tab-pane>


          <el-tab-pane label="Switchs">

            <el-button-group>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="chauffageOn">Chauffage ON</el-button>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="chauffageOff">Chauffage OFF</el-button>
            </el-button-group>

          </el-tab-pane>


          <el-tab-pane label="Manon">

            <el-button-group>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="castor">Pere Castor</el-button>

              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="yakari">Yakari</el-button>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="trotro">Tro tro</el-button>

              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="ouioui">Oui oui</el-button>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="tchoupi">Tchoupi</el-button>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="sam">Sam le pompier</el-button>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="pig">Peppa pig</el-button>
            </el-button-group>

            <el-button-group>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="henrides">henriDes</el-button>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="tata">tata</el-button>
            </el-button-group>

            <el-button-group>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="poulerousse">Poule rousse</el-button>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="laBelleEtLaBete">La belle et la bete</el-button>
            </el-button-group>
          </el-tab-pane>




          <el-tab-pane label="Nous">
            <el-button-group>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="camille">Camille</el-button>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="floyd">Pink floyd</el-button>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="tetesRaides">Tetes raides</el-button>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="hang">Hang massive</el-button>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="vargas">Vargas</el-button>
            </el-button-group>

          </el-tab-pane>


          <el-tab-pane label="Sensors">
            <div>
              <h3>Temperature : {{ sensors.temperature }}</h3>
              <h3>Humidité : {{ sensors.humidity }}</h3>
              <h3>Pression : {{ sensors.pressure }}</h3>
              <h3>Luminosité : {{ sensors.luminosity }}</h3>
            </div>

          </el-tab-pane>



          <el-tab-pane label="Download">
            <h2>Actuel download preload speed : {{ downloadSpeed }}</h2>

            <el-button-group>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="downloadSpeedSlow">Slow speed</el-button>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="downloadSpeedNormal">Normal speed</el-button>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="downloadSpeedHigh">High speed</el-button>
            </el-button-group>

            <div class="logs">
              <div v-for="log in downloadLogs">
                {{ log }}
              </div>
            </div>

          </el-tab-pane>

          <el-tab-pane label="Daemon">

            <el-button-group>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="restartZigateListener">Restart zigate listener</el-button>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="restartZigateBroker">Restart zigate broker</el-button>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="restartBroadlinkBroker">Restart Broadlink broker</el-button>
            </el-button-group>
            <el-button-group>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="restartMpd">Restart mpd</el-button>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="restartMopidy">Restart mopidy</el-button>

            </el-button-group>
            <el-button-group>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="restartPi">Restart PI</el-button>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="shutdownPi">Shutdown PI</el-button>
            </el-button-group>

          </el-tab-pane>

          <el-tab-pane label="DownloadFrame">

            <iframe width="90%" height="90%" src="./downloaded/"></iframe>
          </el-tab-pane>



        </el-tabs>

      </el-col>
    </el-row>
  </div>
</template>

<script>
// Set the parent router view.
// The parent route component is Home.vue in pages.
// Loads home.vue from here.

var dashboardBaseUrl = document.location.protocol+"//dashboard.home.chooo7.com/";

import Vue from 'vue'

  export default {
    name: 'PicturePlayer',
    data: function(){
      return {
        ikea1LampIntensity: 50,
        isRetrievingIkeaLampValue: false,
        changeLampTimeout: false,
        loadingCounter: 0,
        facts: {},
        downloadSpeed: "??",
        downloadLogs: "",
        mounts: {
          chooo7: true,
          antho: true
        },
        daemons_state: {
          zigate_listener: true,
          zigate_broker: true,
          broadlinkbroker: true,
          mpc: true,
          mmpc: true,
        },
        sensors: {
          temperature: "loading",
          pression: "loading",
          humidity: "loading",
          luminosity: "loading",
          ikeaLamp1_current_level: "loading"
        }
      }
    },
    watch: {
      ikea1LampIntensity: function(newValue, oldValue) {
        var self=this;
        console.log('changed', oldValue, newValue);
        if(self.isRetrievingIkeaLampValue)
        {
          console.log('Abandon');
          self.isRetrievingIkeaLampValue = false;
          return true;
        }

        if(self.changeLampTimeout)
        {
          clearTimeout(self.changeLampTimeout);
        }
        self.changeLampTimeout = setTimeout(function() {
          console.log('in timeout');
          self.loadingCounter++;
          self.$http.get(dashboardBaseUrl + "homecmd.php?action=ikeaLampSetIntensity&param=" + encodeURIComponent(newValue)).then(response => {
            self.loadingCounter--;
          },
          error =>
          {
            self.loadingCounter--;
          });
        }, 200);

      },
      "sensors.ikeaLamp1_current_level": function() {
        var self=this;
        console.log('changed');
        console.log(self.sensors.ikeaLamp1_current_level);
        self.isRetrievingIkeaLampValue = true;
        self.ikea1LampIntensity = parseInt(self.sensors.ikeaLamp1_current_level);
      },
      "mounts.chooo7": function() {
        var self = this;
        if(self.mounts.chooo7) {
          this.$notify.success({
            title: 'Mount Chooo7 OK',
            message: 'Le point de montage chooo7 est OK',
            duration: 0
          });
        }else{
          this.$notify.error({
            title: 'Mount Chooo7 DEAD',
            message: 'Le point de montage chooo7 est défaillant',
            duration: 0
          });
        }
      },
      "daemons_state.zigate_listener": function() {
        var self = this;
        if(self.daemons_state.zigate_listener) {
          this.$notify.success({
            title: 'Zigate Listener OK',
            message: 'Zigate Listenerest OK',
            duration: 0
          });
        }else{
          this.$notify.error({
            title: 'Zigate Listener DEAD',
            message: 'Zigate Listener est défaillant',
            duration: 0
          });
        }
      },
      "daemons_state.zigate_broker": function() {
        var self = this;
        if(self.daemons_state.zigate_broker) {
          this.$notify.success({
            title: 'zigate_broker OK',
            message: 'zigate_broker est OK',
            duration: 0
          });
        }else{
          this.$notify.error({
            title: 'zigate_broker DEAD',
            message: 'zigate_broker est défaillant',
            duration: 0
          });
        }
      },
      "daemons_state.broadlinkbroker": function() {
        var self = this;
        if(self.daemons_state.broadlinkbroker) {
          this.$notify.success({
            title: 'Broadlink broker OK',
            message: 'Broadlink broker est OK',
            duration: 0
          });
        }else{
          this.$notify.error({
            title: 'Broadlink broker DEAD',
            message: 'Broadlink broker est défaillant',
            duration: 0
          });
        }
      },
      "daemons_state.mpc": function() {
        var self = this;
        if(self.daemons_state.mpc) {
          this.$notify.success({
            title: 'MPC OK',
            message: 'MPC est OK',
            duration: 0
          });
        }else{
          this.$notify.error({
            title: 'MPC DEAD',
            message: 'MPC est défaillant',
            duration: 0
          });
        }
      },
      "daemons_state.mmpc": function() {
        var self = this;
        if(self.daemons_state.mmpc) {
          this.$notify.success({
            title: 'Mopidy OK',
            message: 'Mopidy est OK',
            duration: 0
          });
        }else{
          this.$notify.error({
            title: 'Mopidy DEAD',
            message: 'Mopidy est défaillant',
            duration: 0
          });
        }
      }
    },
    methods: {
      initComponent() {
        var self = this;
        self.refreshInformations();
      },

      refreshInformations: function() {
        var self = this;
        self.loadMountStates();
        self.loadDownloadState();
        self.loadDaemonState();
        self.loadSensorState();
        self.loadFacts();
        setTimeout(function() {self.refreshInformations();}, 30000);
      },

      loadMountStates: function() {
        this.loadMountStateChooo7();
      },
      loadMountStateChooo7: function() {
        var self = this;
        self.loadingCounter++;
        self.$http.get(dashboardBaseUrl+"mountstate.php?config=chooo7").then(response => {
          self.loadingCounter--;
          if(response.body != self.mounts.chooo7) {
            self.mounts.chooo7 = response.body;
          }
        }, error => {
            self.loadingCounter--;
            console.error(error);
        });
      },

      loadDownloadState: function() {
        var self = this;
        self.loadingCounter++;
        self.$http.get(dashboardBaseUrl+"downloadstate.php?config=chooo7").then(response => {
          self.loadingCounter--;
        self.downloadLogs = response.body.logs;
        self.downloadSpeed = response.body.speed;
      }, error => {
          self.loadingCounter--;
          console.error(error);
        });

      },


      loadDaemonState: function() {
        var self = this;
        self.loadingCounter++;
        self.$http.get(dashboardBaseUrl+"daemonstate.php?config=chooo7").then(response => {
          self.loadingCounter--;
        self.daemons_state = response.body;
        }, error => {
          self.loadingCounter--;
          console.error(error);
        });

      },



      loadSensorState: function() {
        var self = this;
        self.loadingCounter++;
        self.$http.get(dashboardBaseUrl+"sensorstate.php?config=chooo7").then(response => {
          self.loadingCounter--;
        self.sensors = response.body;
        console.log('sensors', self.sensors);
      }, error => {
          self.loadingCounter--;
          console.error(error);
        });

      },

      loadFacts: function() {
        var self = this;
        self.loadingCounter++;
        self.$http.get(dashboardBaseUrl+"facts.php").then(response => {
          self.loadingCounter--;
        self.facts = response.body;
        console.log('facts', self.facts);
      }, error => {
          self.loadingCounter--;
          console.error(error);
        });

      },

      launchAction: function(evt)
      {
        var self=this;
        var action = jQuery(evt.target).attr('data-do');
        if(action == null)
        {
          action = jQuery(evt.target).parents('.el-button').attr('data-do');
        }
        
        self.loadingCounter++;
        self.$http.get(dashboardBaseUrl+"homecmd.php?action="+action).then(response => {
          self.loadingCounter--;
        }, error => {
          self.loadingCounter--;
        });
      }
    },
    created() {
      var self=this;
      self.initComponent();
    }
  }
</script>

<style scoped lang="scss">

  @import '../../node_modules/element-theme-default/lib/index.css';
  .home{
    .loadingZone
    {
      position: absolute;
      top: 0px;
      left: 0px;
      font-size: 20px;
    }
  }
  .picture-player
 {
   .main-picture
   {
     max-width: 100%;
     max-height: 100%;
   }
   .footer{
     z-index: 2020;

     position: fixed;
     bottom: 0;
     left: 50%;
   }
 }
</style>