<template>
    <div class="app-container">

        <div class="search-container">

            <label class="sub-title">基金代码或名称:</label>
            <el-input size="small" style="width: 250px;" v-model="searchData.fund" placeholder="输入基金代码或名称" @keyup.enter.native="search"></el-input>

            <label class="sub-title">持仓股票:</label>
            <el-input size="small" style="width: 250px;" v-model="searchData.stock" placeholder="输入股票代码或名称" @keyup.enter.native="search"></el-input>

            <el-button type="success" size="small" @click="search" style="margin-left: 5px;">搜索</el-button>
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

        <el-dialog title="持仓明细" :visible.sync="dialogVisible">
            <el-table :data="fundCcmxData">
                <el-table-column property="stock_code" label="股票代码" width="150"></el-table-column>
                <el-table-column property="stock_name" label="股票名称" width="200"></el-table-column>
                <el-table-column property="report_date" label="最后更新日期" width="200"></el-table-column>
            </el-table>
        </el-dialog>

    </div>
</template>
<script>
    import CommonTable from '../../components/CommonTable'
    import {mergeJson} from "../../utils";
    import {getFundCcmxList, getFundDataList} from "../../api/demo/fund";

    export default {
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
                        {prop: 'id', label: 'ID', width: "100"},
                        {prop: 'code', label: '基金代码', width: "150"},
                        {prop: 'name', label: '基金名称', width: "300"}
                    ],
                    operatesBtn: [
                        {
                            label: '查看持仓明细',
                            type: 'normal',
                            functionName: 'detail',
                            icon: "el-icon-view"
                        },

                    ]
                },
                dialogVisible: false,
                searchData: {
                    fund: '',
                    stock:''
                },
                fundCcmxData:[]

            }
        },
        mounted() {
            //初始化
            this.getDataList();
        },
        methods: {
            // 搜索
            search() {
                this.handleCurrentChange(1)
            },
            //查看详情
            async handleDetail(row) {
                this.dialogType = 'edit'
                this.dialogVisible = true

                var response = await getFundCcmxList(row.code)

                this.fundCcmxData = response.data.data
            },
            //获取数据
            async getDataList() {
                //拼装分页和查询参数
                var params = mergeJson(this.commonTable.pages, this.searchData)
                var response = await getFundDataList(params)


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
            // 操作按钮
            handleCommand(command, row) {
                switch (command) {
                    case 'detail':
                        this.handleDetail(row)
                        break;

                }
            }
        }
    }
</script>
