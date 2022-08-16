<template>
    <div class="app-container">
        <div class="top-btn-container">
            <el-button type="primary" size="small" @click="handleAdd()" icon="el-icon-plus">新增网站</el-button>
        </div>

        <div class="search-container">
            <el-input size="small" style="width: 150px;" v-model="searchData.name" placeholder="输入网站名称" @keyup.enter.native="search"></el-input>

            <el-select size="small" v-model="searchData.type_id" >
                <el-option  label="--请选择网站分类--" :value="-1"></el-option>
                <el-option
                        v-for="item in siteTypeOptions"
                        :key="item.id"
                        :label="item.name"
                        :value="item.id">
                </el-option>
            </el-select>

            <el-select size="small" v-model="searchData.status" placeholder="状态">
                <el-option label="--请选择状态--" :value="-1"></el-option>
                <el-option
                        v-for="item in statusOptions"
                        :key="item.id"
                        :label="item.name"
                        :value="item.id">
                </el-option>
            </el-select>

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

        <!--模态框-->
        <el-dialog :visible.sync="dialogVisible" :title="dialogType==='edit'?'编辑':'新增'">
            <el-form :model="dataInfo" :rules="rules" ref="ruleForm" label-width="130px" label-position="left">
                <el-form-item label="网站id" prop="id">
                    <el-input v-model="dataInfo.id" placeholder="网站id" disabled/>
                </el-form-item>
                <el-form-item label="网站名称" prop="name">
                    <el-input v-model="dataInfo.name" placeholder="网站名称" aria-required="true"/>
                </el-form-item>
                <el-form-item label="网站名称(英文)" prop="username">
                    <el-input v-model="dataInfo.english_name" placeholder="网站名称(英文)" aria-required="true"/>
                </el-form-item>
                <el-form-item label="网站地址" prop="url">
                    <el-input v-model="dataInfo.url" placeholder="网站地址" aria-required="true"/>
                </el-form-item>

                <el-form-item label="网站分类" prop="type_id">
                    <el-select v-model="dataInfo.type_id" placeholder="请选择网站分类">
                        <el-option
                                v-for="item in siteTypeOptions"
                                :key="item.id"
                                :label="item.name"
                                :value="item.id">
                        </el-option>
                    </el-select>
                </el-form-item>

                <el-form-item label="是否需要登录访问" prop="is_login">
                    <el-radio v-model="dataInfo.is_login" :label="0">不需要</el-radio>
                    <el-radio v-model="dataInfo.is_login" :label="1">需要</el-radio>
                </el-form-item>

                <el-form-item label="登录cookie" prop="login_cookie">
                    <el-input type="textarea"   :rows="12" v-model="dataInfo.login_cookie" placeholder="登录cookie" aria-required="true"/>
                </el-form-item>


                <el-form-item label="状态" prop="status">
                    <el-radio v-model="dataInfo.status" :label="0">禁用</el-radio>
                    <el-radio v-model="dataInfo.status" :label="1">启用</el-radio>
                </el-form-item>


            </el-form>
            <div style="text-align:right;">
                <el-button type="danger" @click="cancelForm('ruleForm')">关闭</el-button>
                <el-button type="primary" @click="confirmRole('ruleForm')">提交</el-button>
            </div>
        </el-dialog>


        <!--模态框-->
        <el-dialog title="执行结果" :visible.sync="runDialogVisible" >
            <el-table :data="runResult">
                <el-table-column property="title" label="标题"></el-table-column>
                <el-table-column property="url" label="网址"></el-table-column>
            </el-table>
        </el-dialog>
    </div>
</template>
<script>
    import CommonTable from '../../components/CommonTable'
    import {mergeJson} from "../../utils";
    import {getSiteConfigDataList, getSiteTypeOptionDataList, runSite, siteConfigDelete, siteConfigSave} from "../../api/hotArticle/site";

    export default {
        components: {CommonTable},
        data() {
            return {
                commonTable: {
                    loading: true,
                    showSummary:false,
                    exportExcelBtn: true,
                    totalCount: 0,
                    pages: {
                        pageSize: 10,
                        currentPage: 1,
                    },
                    dataList: [],
                    columns: [
                        {prop: 'id', label: 'id', width: "100"},
                        {prop: 'name', label: '网站名称', width: "150"},
                        {prop: 'english_name', label: '网站名称(英文)', width: "150"},
                        {prop: 'url', label: '网站url', width: "250"},
                        {prop: 'type_name', label: '网站分类', width: "100"},
                        {
                            prop: 'is_login', label: '是否需要登录验证', width: "200", formatter: (row) => {
                                if (row.is_login == 1) {
                                    return "<el-tag class='el-tag el-tag--success el-tag--light' >需要</el-tag>";
                                } else {
                                    return '<el-tag class="el-tag el-tag--danger el-tag--light" >不需要</el-tag>';
                                }
                            }
                        },
                        {
                            prop: 'status', label: '状态', width: "100", formatter: (row) => {
                                if (row.status == 1) {
                                    return "<el-tag class='el-tag el-tag--success el-tag--light' >启用</el-tag>";
                                } else {
                                    return '<el-tag class="el-tag el-tag--danger el-tag--light" >禁用</el-tag>';
                                }
                            }
                        },
                        {prop: 'created_at', label: '创建时间', width: "170"},
                        {prop: 'updated_at', label: '更新时间', width: "170"}
                    ],
                    operatesBtn: [
                        {
                            label: '编辑',
                            type: 'normal',
                            functionName: 'edit',
                            icon: "el-icon-edit"
                        },
                        {
                            label: '删除',
                            type: 'danger',
                            functionName: 'delete',
                            icon: "el-icon-delete"
                        },
                        {
                            label: '手动执行',
                            type: 'normal',
                            functionName: 'runSite',
                            icon: "el-icon-view"
                        },
                    ]
                },

                searchData: {
                    name: '',
                    type_id: -1,
                },
                dialogVisible: false,
                dialogType: 'new',
                dataInfo: [],
                rules: {
                    name: [{required: true, message: '请输入网站名称', trigger: 'blur'}],
                    english_name: [{required: true, message: '请输入网站名称(英文)', trigger: 'blur'}],
                    url: [{required: true, message: '请输入网站地址', trigger: 'blur'}],
                    type_id: [{required: true, message: '请选择网站分类', trigger: 'blur'}],
                },
                siteTypeOptions: [],
                runDialogVisible:false,
                runResult:[],
                statusOptions: [{'id': 1, 'name': "启用"}, {'id': 0, 'name': "禁用"}]
            }
        },
        mounted() {
            //初始化
            this.getDataList();

            //获取网站分类
            getSiteTypeOptionDataList().then(response => {
                this.siteTypeOptions = response.data
            })
        },
        methods: {
            // 搜索
            search() {
                this.handleCurrentChange(1)
            },
            //新增
            handleAdd() {
                this.dialogType = 'new'
                this.dialogVisible = true

                this.dataInfo = {"id": 0, "status": 1}
                this.rules.password = {required: true, message: '请输入密码', trigger: 'blur'}
            },
            //编辑
            handleEdit(row) {
                this.dialogType = 'edit'
                this.dialogVisible = true

                this.dataInfo = JSON.parse(JSON.stringify(row))
                this.dataInfo.password = '';
                this.rules.password = '';
            },
            //删除
            handleDelete(row) {
                this.$confirm('确定要删除此数据吗？', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(async () => {
                    var response = await siteConfigDelete(row.id)
                    this.$message({
                        type: 'success',
                        message: response.msg
                    })
                    this.getDataList()
                }).catch(err => {
                    console.error(err)
                })
            },
            //提交
            confirmRole(formName) {
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        siteConfigSave(this.dataInfo).then(response => {
                            this.dialogVisible = false
                            this.$notify({
                                title: 'Success',
                                dangerouslyUseHTMLString: true,
                                message: response.msg,
                                type: 'success'
                            })
                            this.getDataList()
                        }).catch(error => {

                        })

                    } else {
                        return false;
                    }
                });
            },
            cancelForm(formName) {
                this.$refs[formName].resetFields();
                this.dialogVisible = false
            },
            //获取数据
            async getDataList() {
                //拼装分页和查询参数
                var params = mergeJson(this.commonTable.pages, this.searchData)
                var response = await getSiteConfigDataList(params)


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
                    case 'edit':
                        this.handleEdit(row)
                        break;
                    case 'delete':
                        this.handleDelete(row)
                        break;
                    case 'runSite':
                        this.handleRunSite(row)
                        break;
                }
            },
            handleRunSite(row) {
                const loading = this.$loading({
                    lock: true,
                    text: 'Loading',
                    spinner: 'el-icon-loading',
                    background: 'rgba(0, 0, 0, 0.7)'
                });

                this.dataInfo = JSON.parse(JSON.stringify(row))
                runSite(this.dataInfo.id).then(response => {
                    loading.close();
                    this.runDialogVisible = true

                    let result = [];

                    for (var i in response.data){
                        result.push({"title":i,"url":response.data[i]})
                    }
                    console.log(result)
                    this.runResult = result
                }).catch(error => {
                    loading.close();
                    console.log(error)
                })

            },
        }
    }
</script>
