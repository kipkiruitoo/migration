<template>
  <div>
    <v-menu v-if="requiredate">
      <v-text-field
        slot="activator"
        :value="date"
        label="Date of Consumption"
        prepend-icon="date_range"
        readonly
        max-width="290"
      ></v-text-field>
      <v-date-picker
        v-model="date"
        color="green lighten-1"
        @change="checkdate"
        id="date"
        :max="new Date().toISOString().substr(0, 10)"
      ></v-date-picker>
    </v-menu>
    <div class="form-group">
      <label for="locale">Select Survey Language</label>
      <select v-model="slocale" @change="onlocaleChange" id="locale" class="form-control">
        <option value="en">English</option>
        <option value="bg">Swahili</option>
        <option value="it">Vernacular</option>
        <option value="de">Maasai</option>
        <option value="pl">Somali</option>
      </select>
    </div>

    <survey v-show="showsurvey" :survey="survey"></survey>
    <div class="timer">
      <BaseTimer :time-left="timeLeft" :time-passed="timePassed" :time-limit="timeLimit" />
    </div>
  </div>
</template>

<script>
import * as SurveyVue from "survey-vue";
import * as swahiliStrings from "../swahili";
var Survey = SurveyVue.Survey;
Survey.cssType = "default";
Survey.sendResultOnPageNext = true;

import format from "date-fns/format";

import * as widgets from "surveyjs-widgets";
import BaseTimer from "./BaseTimer";
Object.filter = (obj, predicate) =>
  Object.keys(obj)
    .filter((key) => predicate(obj[key]))
    .reduce((res, key) => Object.assign(res, { [key]: obj[key] }), {});

const widgetsList = Object.filter(
  SurveyConfig.widgets,
  (widget) => widget === true
);

Object.keys(widgetsList).forEach(function (widget) {
  widgets[widget](SurveyVue);
});

export default {
  components: {
    Survey,
    BaseTimer,
  },
  props: ["surveyData", "selectedphone", "callSession", "jsonData"],

  data() {
    return {
      survey: {},
      respondent: "",
      meta_random: {},
      agent: "",
      date: "",
      jsondata: "",
      project: "",
      phonenumber: 0,
      sid: "",
      count: 0,
      starttime: "",
      endtime: "",
      sessionId: "",
      slocale: "en",
      requiredate: false,
      showsurvey: false,
      nit: 1,
      timeLimit: 6,
      timerInterval: null,
      timePassed: 0,
    };
  },
  created() {
    this.survey = new SurveyVue.Model(this.surveyData.json);
    this.survey.sendResultOnPageNext = true;
    this.nit = this.surveyData.num;
    this.agent = this.surveyData[1];
    this.project = this.surveyData.project;
    this.sid = this.surveyData.id;
    this.respondent = this.surveyData[0];
    this.jsondata = JSON.stringify(this.jsonData);
    this.phonenumber = this.selectedphone;
    // this.sessionId = this.callSession;
    localStorage.setItem("count", this.count);
    localStorage.setItem("phone", this.phonenumber);
    // let today = new Date();
    this.starttime = this.gettime();

    console.log(this.starttime);

    // console.log(this.jsondata);
  },
  methods: {
    startTimer() {
      this.timerInterval = setInterval(() => (this.timePassed += 1), 1000);
    },
    checkdate() {
      let url = `/check-date`;

      axios
        .post(url, {
          date: this.date,
          respondent: this.respondent.respondent,
        })
        .then((response) => {
          console.log(response.data.exists);
          if (response.data.exists) {
            this.$toastr.i(
              "An  Interview Already Exists for this Respondent for that Date"
            );
            this.showsurvey = false;
          } else {
            this.showsurvey = true;
          }
        });
    },
    sendResults() {
      this.timeLimit = 10;
      this.timePassed = 0;
      //   this.startTimer();
      let url = "/incomplete";
      var resultAsString = JSON.stringify(this.survey.getPlainData);
      // alert(resultAsString); //send Ajax request to your web server.
      axios
        .post(url, {
          json: this.survey.data,
          agent: this.agent,
          respondent: this.respondent.respondent,
          survey: this.sid,
          // phonenumber: this.phonenumber,
          project: this.surveyData.project,
          // date: this.date
        })
        .then((result) => {
          console.log(result);
          if (result.data.message === "Success") {
            this.$toastr.i("Progress Successfully Saved");
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    loadResults() {
      console.log(this.jsondata);
      this.survey.data = JSON.parse(this.jsondata);
      if (this.jsondata != "null") {
        // alert("An incomplete Interview Exists for this user");
        this.$toastr.i("An Unfinished Interview Exists for this Respondent");
      }
    },
    onlocaleChange() {
      this.survey.locale = this.slocale;
      console.log(this.slocale);
      this.survey.render();
    },
    gettime() {
      var today = new Date();
      var date =
        today.getFullYear() +
        "-" +
        (today.getMonth() + 1) +
        "-" +
        today.getDate();
      var time =
        today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
      var dateTime = date + " " + time;

      return dateTime;
    },
  },
  computed: {
    formattedDate() {
      return this.date ? format(this.date, "dddd, MMMM Do YYYY") : "";
    },
    timeLeft() {
      return this.timeLimit - this.timePassed;
    },
  },
  watch: {
    page() {
      this.loadResults();
      this.survey.sendResultOnPageNext = true;
      this.survey.data = this.jsondata;
      // console.log(this.survey.data);
    },
  },
  mounted() {
    this.startTimer();
    window.setInterval(() => {
      this.sendResults();
    }, 10000);
    this.survey.sendResultOnPageNext = true;
    this.loadResults();
    this.$toastr.defaultClassNames = ["animated", "zoomInUp"];
    this.$toastr.defaultTimeout = 3000;
    this.$toastr.defaultPosition = "toast-top-center";

    if (this.project == 24) {
      this.requiredate = true;
      //   this.showsurvey = true;
    } else {
      this.showsurvey = true;
    }

    var min = new Date().getMinutes() % 2;

    var locationChoices = [
        "good",
        "poor"
    ]


      var equalityChoices = [
        "equally",
        "unequally"
    ]


    var fineChoices = [
        "large",
        "small"
    ]

    var contributionChoices = [
        "large",
        "small"
    ]

    var earningsChoices = [
        30000,
        120000
    ]

    var likelyChoices = [
        "likely",
        "unlikely"
    ]


    var taxChoices = [
        "a lot of",
        "very little"
    ]


    var locationChoice = locationChoices[Math.floor(Math.random()*locationChoices.length)]
    var equalityChoice = equalityChoices[Math.floor(Math.random()*equalityChoices.length)]
    var fineChoice  =  fineChoices[Math.floor(Math.random()*fineChoices.length)]
    var contributionChoice  =  contributionChoices[Math.floor(Math.random()*contributionChoices.length)]
    var earningChoice = earningsChoices[Math.floor(Math.random()*earningsChoices.length)]
    var likelyChoice =  likelyChoices[Math.floor(Math.random()*likelyChoices.length)]
    var taxChoice = taxChoices[Math.floor(Math.random()*taxChoices.length)]



    if(min == 0){
            var caseName = 'John';
            this.survey.setVariable('caseName', 'John');
            this.survey.setVariable('casePronoun', 'He');
            this.survey.setVariable('his_herPronoun', 'His');
            this.survey.setVariable('caseLocation', locationChoice )
            this.survey.setVariable('caseEquality', equalityChoice)
            this.survey.setVariable('caseFine', fineChoice)
            this.survey.setVariable('caseContibution', contributionChoice)
            this.survey.setVariable('caseEarnings', earningChoice)
            this.survey.setVariable('caseLikely', likelyChoice)
            this.survey.setVariable('caseTax', taxChoice)
    }else{
            var caseName = 'Mary';
            this.survey.setVariable('caseName', 'Mary');
            this.survey.setVariable('casePronoun', 'She');
            this.survey.setVariable('his_herPronoun', 'Her');
             this.survey.setVariable('caseLocation', locationChoice )
             this.survey.setVariable('caseContibution', contributionChoice)
            this.survey.setVariable('caseEquality', equalityChoice)
            this.survey.setVariable('caseFine', fineChoice)
            this.survey.setVariable('caseEarnings', earningChoice)
            this.survey.setVariable('caseLikely', likelyChoice)
            this.survey.setVariable('caseTax', taxChoice)
    }

    var selectedMetadata = {
        'randomized_name' : caseName,
        'randomized_location' : locationChoice,
        'randomized_contribution': contributionChoice,
        'randomized_equality' : equalityChoice,
        'randomized_earning' : earningChoice,
        'randomized_fine':fineChoice,
        'randomized_likelyhood': likelyChoice,
        'randomized_tax_info_provided': taxChoice
    }

    this.meta_random = selectedMetadata;
// if (this.project == 90) {
//     alert('For Question 11 use the following names: ' + JSON.stringify(this.meta_random))
// }


    this.survey.onComplete.add((result) => {
      let url = `/survey/${this.surveyData.id}/result`;
      if (this.project == 90) {
      result.setValue('meta_random', this.meta_random);
      }
      axios
        .post(url, {
          json: this.survey.data,
          agent: this.agent,
          respondent: this.respondent.respondent,
          survey: this.sid,
          start_time: this.starttime,
          end_time: this.gettime(),
          callsession: window.CallSession,
          phonenumber: this.phonenumber,
          project: this.surveyData.project,
          date: this.date,
        })
        .then((response) => {
          console.log(response);
          this.count++;
          localStorage.setItem("count", this.count);
          console.log(this.count);
          if (this.count == this.nit) {
            this.$toastr.s("Interview Successfully Finished");
            window.history.back();
          } else {
            this.survey.clear();
            this.$toastr.i(
              "The  interview has been successfully saved",
              "Success"
            );
            this.survey.render();
          }
        });
    });
    this.survey.onPartialSend.add((result) => {
      let url = "/incomplete";
      var resultAsString = JSON.stringify(this.survey.getPlainData);
      // alert(resultAsString); //send Ajax request to your web server.
      axios
        .post(url, {
          json: result.data,
          agent: this.agent,
          respondent: this.respondent.respondent,
          survey: this.sid,
          // phonenumber: this.phonenumber,
          project: this.surveyData.project,
          // date: this.date
        })
        .then((result) => {
          console.log(result);
          if (result.data.message === "Success") {
            this.$toastr.i("Progress Successfully Saved");
          }
        })
        .catch((error) => {
          console.log(error);
        });
    });
  },
};
</script>
<style lang="scss" >
.sv_q_description span {
  color: red;
  font-size: large;
}
.timer {
  position: fixed;
  width: 80px;
  height: 80px;
  bottom: 40px;
  right: 40px;
  //   background-color: #0c9;
  //   color: #fff;
  //   border-radius: 50px;
  //   text-align: center;
  //   box-shadow: 2px 2px 3px #999;
}
</style>
