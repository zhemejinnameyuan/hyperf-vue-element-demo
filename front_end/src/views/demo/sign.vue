<template>
    <div class="app-container">
        <el-button @click="doSign()">签到</el-button>
        <el-calendar>
            <!-- 这里使用的是 2.5 slot 语法，对于新项目请使用 2.6 slot 语法-->
            <template
                    slot="dateCell"
                    slot-scope="{date, data}">
                <p :class="data.isSelected ? 'is-selected' : ''">
                    {{data.day}} {{findDays(data.day )}}
                </p>
            </template>
        </el-calendar>
    </div>
</template>

<style>
    .is-selected {
        color: #1989FA;
    }
</style>
<script>


    import {doSign, getSignDays} from "../../api/demo/sign";

    export default {
        data() {
            return {
                calendar: {
                    'signDays':[], //已签到天数
                }
            };
        },
        methods: {
            findDays(day){
                if(this.calendar.signDays.indexOf(day) != -1){
                    return '✔';
                }else{
                    return  ''
                }
            },
            getSignDays(){
                getSignDays().then(response=>{
                   if(response.code == 0){
                       this.calendar.signDays = response.data
                   }
                });
            },
            doSign(){
                doSign().then(response=>{
                    if (response.code == 0) {
                        this.$message.success(response.message)
                    } else {
                        this.$message.error(response.message)
                    }

                    //渲染
                    this.getSignDays()

                });

            }
        },
        mounted() {
            this.getSignDays();
        }
    }
</script>
