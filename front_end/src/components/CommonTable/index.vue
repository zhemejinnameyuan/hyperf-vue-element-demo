<template>
  <div>
    <el-button v-if="exportExcelBtn" style="float: right;margin-bottom: 5px" size="small" @click="exportExcel()"><i class="el-icon-download"></i>导出Excel</el-button>
    <!--表格-->
    <el-table stripe id="CommonTable" :data="dataList"
              :show-summary="showSummary"
              v-loading="tableLoading" style="width: 100%;" border>
      <!--数据列-->
      <el-table-column
              v-for="(column, index) in columns"
              :prop="column.prop"
              :key="index"
              :label="column.label"
              :width="column.width"
      >
        <template slot-scope="scope">
          <template v-if="column.formatter">
            <div v-html="column.formatter(scope.row)"></div>
          </template>
          <template v-else>
            <span>{{scope.row[column.prop]}}</span>
          </template>
        </template>
      </el-table-column>

      <!--按钮操作组-->
      <el-table-column label="操作" v-if="operatesBtn.length > 0"  fixed="right" :min-width="operatesBtn.length*100">
        <template slot-scope="scope">
          <el-button
                  v-for="(btn,key) in operatesBtn"
                  :key="key"
                  :type=btn.type
                  size="small"
                  @click="handleCommand(btn.functionName,scope.row)"
                  :icon=btn.icon
          >
            {{btn.label}}
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <!--分页-->
    <el-pagination
            class="pagination"
            @size-change="handleSizeChange"
            @current-change="handleCurrentChange"
            :current-page="pages.currentPage"
            :page-sizes="[10, 20, 30,50,100]"
            :page-size="pages.pageSize"
            layout="total, sizes, prev, pager, next, jumper"
            :total="totalCount"
            :hide-on-single-page="true"
    >
    </el-pagination>

  </div>
</template>
<script>
  export default {
    name: 'CommonTable',
    props: {
      dataList: {
        type: Array,
        default: []
      },
      showSummary: {
        type: Boolean,
        default: false
      },
      pages: {
        type: Object,
        default: null
      },
      totalCount: {
        type: Number,
        default: 0
      },
      columns: {
        type: Array,
        default: []
      },
      operatesBtn: {
        type: Array,
        default: []
      },
      tableLoading: {
        type: Boolean,
        default: false
      },
      exportExcelBtn: {
        type: Boolean,
        default: false
      }
    },
    methods: {
      // 切换每页显示的数量
      handleSizeChange(size) {
        this.$emit('handleSizeChange', size);
      },
      // 切换页码
      handleCurrentChange(index) {
        this.$emit('handleCurrentChange', index);
      },
      // 操作按钮
      handleCommand(command, row) {
        this.$emit('handleCommand', command, row);
      },
      //下载excel
      exportExcel() {
        import('@/vendor/Export2Excel').then(excel => {
          const tHeader = this.array_column(this.columns, 'label')
          const filterVal = this.array_column(this.columns, 'prop')
          const data = this.formatJson(filterVal, this.dataList)
          excel.export_json_to_excel({
            header: tHeader,
            data,
            filename: this.filename,
            autoWidth: this.autoWidth,
            bookType: this.bookType
          })
        })
      },
      formatJson(filterVal, jsonData) {
        return jsonData.map(v => filterVal.map(j => {
          if (j === 'timestamp') {
            return parseTime(v[j])
          } else {
            return v[j]
          }
        }))
      },
      array_column(array = [], column_name = '') {
        return array.map((item, index) => item[column_name])
      }
    }
  }
</script>
