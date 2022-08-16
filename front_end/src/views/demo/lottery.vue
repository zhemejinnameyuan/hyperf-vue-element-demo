<template>
    <div class="app-container">
        <lottery
                @lotteryClick="lotteryClick"
                @lotteryDone="lotteryDone"
                :lottery-start="lottery.start"
                :lottery-prizenum="lottery.prizenum"
                :lottery-prizeno="lottery.prizeno"
                lottery-bg="https://venler.github.io/demo/vue-lottery/static/turnplate-bg.png"
                content-bg="https://venler.github.io/demo/vue-lottery/static/turntable.png"
                pointer-bg="https://venler.github.io/demo/vue-lottery/static/pointer.png"
                :lottery-width="['85%','35%']"
        />

        <div class="marquee">
            <Marquee :data="marquee"></Marquee>
        </div>

    </div>

</template>
<style  >

    .marquee {
        width: 544px;
        padding-left: 100px;
        padding-right: 30px;
        line-height: 50px;
        margin: 0 auto 28px;
        box-sizing: border-box;
        border-radius: 26px;
    }
</style>

<script>
    /**
     props说明
     参数名    参数类型    解释    是否必传
     lottery-prizenum    Number    奖品总数量    是
     lottery-prizeno    Number    从起点开始逆时针算，中奖的是第几个    是
     lottery-bg    String    背景图    否
     content-bg    String    内容区域背景图    是
     pointer-bg    String    指针背景图    是
     lottery-start    Number    0为停止，1为开始转动    否
     lotteryDone    Function    旋转完成后的回调函数    否
     lotteryClick    Function    点击抽奖按钮的回调    否
     lottery-width    String    内外圈间距百分比    否
     */
</script>


<script>
    import Marquee from '../../components/Marquee'
    import {submitLottery} from "../../api/demo/lottery";

    export default {
        components: {Marquee},
        data() {
            return {
                lottery: {
                    'start': 0,
                    'prizenum': 8,
                    'prizeno': 0,
                },
                websocket: null,
                marquee:[
                    '恭喜XXX， 抽中了 一等奖 ',
                    '恭喜XXX， 抽中了 二等奖 ',
                    '恭喜XXX， 抽中了 20积分 ',
                    '恭喜XXX， 抽中了 20积分 ',
                    '恭喜XXX， 抽中了 20积分 ',
                    '恭喜XXX， 抽中了 20积分 ',
                    '恭喜XXX， 抽中了 20积分 ',
                    ]
            };
        },
        created() {
            this.initWebSocket();
        },
        destroyed() {
            this.websocket.close() //离开路由之后断开websocket连接
        },

        methods: {
            //点击抽奖按钮
            lotteryClick() {
                submitLottery().then(response => {
                    this.lottery.start = 1;
                    console.log(response);
                    if(response.code == 0){
                        this.lottery.prizeno = response.data.id;
                    }
                })
            },
            //中奖回调
            lotteryDone(response) {
                console.log(response)
                this.lottery.start = 0;
            },

            initWebSocket(){ //初始化weosocket
                const wsuri = "ws://127.0.0.1:9502/lottery";
                this.websocket = new WebSocket(wsuri);
                this.websocket.onmessage = this.websocketonmessage;
                this.websocket.onopen = this.websocketonopen;
                this.websocket.onerror = this.websocketonerror;
                this.websocket.onclose = this.websocketclose;
            },
            websocketonopen(){ //连接建立之后执行send方法发送数据
                let actions = {"test":"12345"};
                this.websocketsend(123);
            },
            websocketonerror(){//连接建立失败重连
                this.initWebSocket();
            },
            websocketonmessage(e){ //数据接收
                console.log("websocket:"+e.data)
            },
            websocketsend(Data){//数据发送
                this.websocket.send(Data);
            },
            websocketclose(e){  //关闭
                console.log('断开连接',e);
            },

        },
        mounted() {

        }
    }
</script>
