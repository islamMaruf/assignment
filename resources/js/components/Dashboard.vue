<template>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12 col-sm-6 col-md-6">
          <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"
              ><i class="fas fa-cog"></i
            ></span>

            <div class="info-box-content">
              <span class="info-box-text">Departments</span>
              <span class="info-box-number">
                {{ this.dashboard.departments }}
              </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-6">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1"
              ><i class="fas fa-thumbs-up"></i
            ></span>

            <div class="info-box-content">
              <span class="info-box-text">Students</span>
              <span class="info-box-number">
                {{ this.dashboard.students }}
              </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
    <!--/. container-fluid -->
  </section>
</template>

<script>
export default {
  data() {
    return {
      dashboard: {},
    };
  },
  methods: {
    loadDashBoard() {
      axios.get("api/dashboard").then(({ data }) => {
        this.dashboard = data.data;
      });
    },
  },
  mounted() {
    console.log("Component mounted.");
  },
  created() {
    this.loadDashBoard();
  },
};
</script>
