<template>
    <div class="app-container">
        <el-row class="demo-autocomplete">
            <el-col style="width: 500px">
                <div class="sub-title">输入后匹配输入建议</div>
                <el-autocomplete
                        class="inline-input"
                        v-model="input_search_value"
                        :fetch-suggestions="querySearch"
                        placeholder="请输入内容"
                        :trigger-on-focus="false"
                        @select="handleSelect"

                ></el-autocomplete>
            </el-col>
        </el-row>


        <div class="sub-title">
            <div>餐厅：{{suggestion.value}}</div>
            <div>地址：{{suggestion.address}}</div>
        </div>
    </div>
</template>


<script>
    import {getInputSuggestions} from "../../api/demo/suggestions";

    export default {
        data() {
            return {
                input_search_value: '',
                suggestion:{
                    'value':'',
                    'address':'',
                }
            };
        },
        methods: {
            querySearch(queryString, cb) {
                var params = {"keyword": queryString};

                getInputSuggestions(params).then(response => {
                    var results = response.data;
                    cb(results);
                })
            },


            handleSelect(item) {
                this.suggestion.address = item.address
                this.suggestion.value = item.value
            }
        },
        mounted() {

        }
    }
</script>
