<template>
    <div class="app-container">

        <div class="search-container">
            <el-input size="small" style="width: 150px;" v-model="searchData.username" placeholder="输入操作用户名" @keyup.enter.native="search"></el-input>

            <el-input size="small" style="width: 150px;" v-model="searchData.content" placeholder="输入内容" @keyup.enter.native="search"></el-input>


            <el-select size="small" v-model="searchData.business_type" placeholder="业务类型">
                <el-option  label="--请选择业务类型--" :value="-1"></el-option>
                <el-option
                        v-for="item in businessTypeOption"
                        :key="item.id"
                        :label="item.name"
                        :value="item.id">
                </el-option>
            </el-select>

            <el-date-picker
                    v-model="searchData.date"
                    :readonly="false"
                    type="datetimerange"
                    range-separator="至"
                    value-format="yyyy-MM-dd HH:mm:ss"
                    start-placeholder="开始日期"
                    end-placeholder="结束日期">
            </el-date-picker>



            <el-button type="success" size="small" @click="search"  style="margin-left: 5px;">搜索</el-button>
        </div>

        <!--表格组件-->
        <CommonTable
                :tableLoading="commonTable.loading"
                :showSummary="commonTable.showSummary"
                :exportExcelBtn="commonTable.exportExcelBtn"
                :columns="commonTable.columns"
                :dataList="commonTable.dataList"
                :operatesBtn="commonTable.operatesBtn"
                :pages="commonTable.pages"
                :totalCount="commonTable.totalCount"
                @handleSizeChange="handleSizeChange"
                @handleCurrentChange = "handleCurrentChange"
                @handleCommand="handleCommand"
        >
        </CommonTable>


    </div>
</template>
<script>
    import CommonTable from '../../../components/CommonTable'
    import {mergeJson} from "../../../utils";
    import {getOperateLog, getOperateBusinessType} from "../../../api/system/operateLog";

    export default {
        name: 'SystemOperateLogList',
        components: {CommonTable},
        data() {
            return {
                commonTable: {
                    loading: true,
                    showSummary: false,
                    exportExcelBtn: false,
                    totalCount: 0,
                    pages: {
                        pageSize: 10,
                        currentPage: 1,
                    },
                    dataList: [],
                    columns: [
                        {prop: 'id', label: 'id', width: "100"},
                        {prop: 'op_userid', label: '操作用户ID', width: "120"},
                        {prop: 'op_username', label: '操作用户名', width: "120"},
                        {prop: 'business_type', label: '业务类型', width: "120"},
                        {prop: 'business_id', label: '业务ID', width: "120"},
                        {prop: 'content', label: '内容'},
                        {prop: 'created_at', label: '创建时间', width: "170"},
                    ],
                    operatesBtn:[]

                },

                searchData: {
                    op_username: '',
                    content: '',
                    business_type: -1
                },

                businessTypeOption: [],
            }
        },
        mounted() {
            //初始化
            this.getDataList();

            // //获取业务类型
            getOperateBusinessType().then(response => {
                this.businessTypeOption = response.data
            })
        },
        methods: {
            // 搜索
            search() {
                this.handleCurrentChange(1)
            },

            //获取数据
            async getDataList() {
                //拼装分页和查询参数
                let params = mergeJson(this.commonTable.pages, this.searchData)
                let response = await getOperateLog(params)


                this.commonTable.dataList = response.data.data
                this.commonTable.totalCount = response.data.count
                this.commonTable.loading = false
            },
            // 切换每页显示的数量
            handleSizeChange(size) {
                this.commonTable.pages.pageSize = size;
                this.getDataList();
            },
            // 切换页码
            handleCurrentChange(index) {
                this.commonTable.pages.currentPage = index;
                this.getDataList();
            },
            //操作按钮
            handleCommand(command, row) {

            }

        }
    }
</script>
