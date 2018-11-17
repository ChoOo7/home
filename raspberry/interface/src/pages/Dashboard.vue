<template>
  <div class="home">
    <i class="el-icon-loading" v-if="loadingCounter"></i>
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


          <el-tab-pane label="Manon">

            <el-button-group>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="castor">Pere Castore</el-button>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="yakari">Yakari</el-button>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="trotro">Tro tro</el-button>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="ouioui">Oui oui</el-button>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="tchoupi">Tchoupi</el-button>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="sam">Sam le pompier</el-button>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="pig">Peppa pig</el-button>
            </el-button-group>

            <el-button-group>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="poulerousse">Poule rousse</el-button>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="poulerousse">Histoire</el-button>
            </el-button-group>
          </el-tab-pane>



          <el-tab-pane label="Download">
            <h2>Actuel download preload speed : {{ downloadSpeed }}</h2>

            <el-button-group>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="downloadSpeedSlow">Slow speed</el-button>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="downloadSpeedNormal">Normal speed</el-button>
              <el-button class="launchAction" v-on:click.stop.prevent="launchAction" data-do="downloadSpeedHigh">Hight speed</el-button>
            </el-button-group>

            <div class="logs">
              <div v-for="log in downloadLogs">
                {{ log }}
              </div>
            </div>

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

var dashboardBaseUrl = "http://dashboard.home.chooo7.com/";

import Vue from 'vue'

  export default {
    name: 'PicturePlayer',
    data: function(){
      return {
        loadingCounter: 0,
        downloadSpeed: "??",
        downloadLogs: "",
        mounts: {
          chooo7: true,
          antho: true
        }
      }
    },
    watch: {
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
            title: 'Mount Chooo7',
            message: 'Le point de montage chooo7 est défaillant',
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
        setTimeout(function() {self.refreshInformations();}, 5000);
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
      launchAction: function(evt)
      {
        var self=this;
        var action = jQuery(evt.target).attr('data-do');
        console.log('evt', action, evt);
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