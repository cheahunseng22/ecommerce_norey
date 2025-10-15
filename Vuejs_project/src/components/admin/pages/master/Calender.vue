<template>
  <div class="card flex-fill">
    <div class="card-header">
      <h5 class="card-title mb-0">Calendar</h5>
    </div>
    <div class="card-body d-flex">
      <div class="align-self-center w-100">
        <div class="chart">
          <div id="datetimepicker-dashboard" ref="calendar"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import flatpickr from 'flatpickr'
import 'flatpickr/dist/flatpickr.min.css'

export default {
  name: 'Calendar',
  mounted() {
    this.initCalendar()
  },
  methods: {
    initCalendar() {
      const date = new Date(Date.now() - 5 * 24 * 60 * 60 * 1000)
      const defaultDate = date.getUTCFullYear() + "-" + (date.getUTCMonth() + 1) + "-" + date.getUTCDate()
      
      flatpickr(this.$refs.calendar, {
        inline: true,
        prevArrow: "<span title=\"Previous month\">&laquo;</span>",
        nextArrow: "<span title=\"Next month\">&raquo;</span>",
        defaultDate: defaultDate
      })
    }
  }
}
</script>