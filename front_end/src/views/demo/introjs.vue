<template>
    <div class="app-container">
      <div data-title="Welcome!" data-intro="Hello World! 👋" class="card-demo">
        <div class="card shadow--md">
          <div class="card__image" data-intro="Intro.js can highlight on elements">

          </div>
          <div class="card__body" data-title="Farewell!" data-intro="And this is the last step!">
            <h4>Quack Lighthouse</h4>
            <small>
              The Quack Head Lighthouse is a well maintained lighthouse close to St.
              Martins. It is a short, beautiful walk to the lighthouse along the
              seashore.
            </small>
          </div>
        </div>
      </div>
    </div>
</template>


<script>
import Intro from '@/utils/intro'  // 引入写好的配置文件

    export default {
        name: 'DemoIntrojs',
        data() {
            return {
              homeSteps: {
                shifts: {
                  step: '1',
                  intro: '班次管理，配置上下班时间及各种人性化设置。',
                  position: 'top'
                },
                attendGroup: {
                  step: '2',
                  intro: '考勤组管理，设置考勤方式、考勤时间等考勤规则。邀请人功能在这里。',
                  position: 'top'
                },
                attendCount: {
                  step: '3',
                  intro: '考勤统计，随时可查看团队每日/月出勤情况。支持修改打卡结果。',
                  position: 'top'
                },
                writeOff: {
                  step: '4',
                  intro: '核销统计，可查看修改打卡结果的历史记录。',
                  position: 'top'
                }
              }
            };
        },
        methods: {


        },
        mounted() {
          this.$nextTick(() => {
            // 开始
            Intro.start()
          })
          // 退出引导回调函数
          Intro.onexit(function () {

          })

        }
    }
</script>
