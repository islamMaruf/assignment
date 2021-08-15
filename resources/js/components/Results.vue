<template>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3">
          <!-- text input -->
          <div class="form-group">
            <input
              type="text"
              class="form-control"
              v-model="search.name"
              placeholder="Student Name"
              autocomplete="off"
            />
          </div>
        </div>
        <div class="col-sm-3">
          <div class="form-group">
            <input
              type="text"
              v-model="search.department"
              class="form-control"
              placeholder="Department"
              autocomplete="off"
            />
          </div>
        </div>
        <div class="col-sm-3">
          <div class="form-group">
            <input
              v-model="search.gpa"
              type="text"
              class="form-control"
              placeholder="GPA"
              autocomplete="off"
            />
          </div>
        </div>
        <div class="col-sm-3">
          <button class="btn btn-sm btn-primary" @click="searchResults()">
            Search
          </button>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Result List</h3>

              <div class="card-tools">
                <button
                  type="button"
                  class="btn btn-sm btn-primary"
                  @click="newModal"
                >
                  <i class="fa fa-plus-square"></i>
                  Add New
                </button>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Student Name</th>
                    <th>Department</th>
                    <th>GPA</th>
                    <th>Date</th>
                    <th>Created</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(result, key) in results.data" :key="result.id">
                    <td>{{ key + 1 }}</td>
                    <td class="text-capitalize">{{ result.student.name }}</td>
                    <td class="text-capitalize">
                      {{ result.student.department.name }}
                    </td>
                    <td>{{ result.gpa }}</td>
                    <td>{{ result.date }}</td>
                    <td>{{ result.created_at }}</td>

                    <td>
                      <a href="#" @click="editModal(result)">
                        <i class="fa fa-edit blue"></i>
                      </a>
                      /
                      <a href="#" @click="deleteResult(result.id)">
                        <i class="fa fa-trash red"></i>
                      </a>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
              <pagination
                :data="results"
                @pagination-change-page="getResults"
              ></pagination>
            </div>
          </div>
          <!-- /.card -->
        </div>
      </div>
      <!-- Modal -->
      <div
        class="modal fade"
        id="addNew"
        tabindex="-1"
        role="dialog"
        aria-labelledby="addNew"
        aria-hidden="true"
      >
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" v-show="!editmode">Create Result</h5>
              <h5 class="modal-title" v-show="editmode">Update Result</h5>
              <button
                type="button"
                class="close"
                data-dismiss="modal"
                aria-label="Close"
              >
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <!-- <form @submit.prevent="createUser"> -->

            <form @submit.prevent="editmode ? updateResult() : createResult()">
              <div class="modal-body">
                <div class="form-group">
                  <label>Student</label>
                  <select
                    name="student"
                    v-model="form.student"
                    id="student"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.has('department') }"
                  >
                    <option value="">Select Student</option>
                    <option
                      v-for="student in students"
                      :key="student.id"
                      :value="student.id"
                    >
                      {{ student.name.toUpperCase() }}
                    </option>
                  </select>
                  <has-error :form="form" field="student"></has-error>
                </div>
                <div class="form-group">
                  <label>GPA</label>
                  <input
                    v-model="form.gpa"
                    type="text"
                    name="name"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.has('gpa') }"
                  />
                  <has-error :form="form" field="gpa"></has-error>
                </div>
                <div class="form-group">
                  <label>Date</label>
                  <input
                    v-model="form.date"
                    type="date"
                    name="date"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.has('date') }"
                  />
                  <has-error :form="form" field="date"></has-error>
                </div>
              </div>
              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-secondary"
                  data-dismiss="modal"
                >
                  Close
                </button>
                <button v-show="editmode" type="submit" class="btn btn-success">
                  Update
                </button>
                <button
                  v-show="!editmode"
                  type="submit"
                  class="btn btn-primary"
                >
                  Create
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  data() {
    return {
      url_search: "",
      search: {
        name: "",
        department: "",
        gpa: "",
      },
      editmode: false,
      results: {},
      students: [],
      form: new Form({
        id: "",
        gpa: "",
        student: "",
        date: "",
      }),
    };
  },
  methods: {
    searchResults() {
      const { name, department, gpa } = this.search;

      axios
        .get("api/results", {
          params: {
            name: name,
            department: department,
            gpa: gpa,
          },
        })
        .then(({ data }) => {
          this.results = data.data;
        });
    },

    getResults(page = 1) {
      this.$Progress.start();

      axios
        .get("api/results?page=" + page)
        .then(({ data }) => (this.results = data.data));

      this.$Progress.finish();
    },
    updateResult() {
      this.$Progress.start();
      // console.log('Editing data');
      this.form
        .put("api/results/" + this.form.id)
        .then((response) => {
          // success
          $("#addNew").modal("hide");
          Toast.fire({
            icon: "success",
            title: response.data.message,
          });
          this.$Progress.finish();
          //  Fire.$emit('AfterCreate');

          this.loadResults();
        })
        .catch(() => {
          this.$Progress.fail();
        });
    },
    editModal(result) {
      this.editmode = true;
      this.form.reset();
      $("#addNew").modal("show");
      this.form.fill(result);
      this.form.student = result.student.id;
    },
    newModal() {
      this.editmode = false;
      this.form.reset();
      $("#addNew").modal("show");
    },
    deleteResult(id) {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!",
      }).then((result) => {
        // Send request to the server
        if (result.value) {
          this.form
            .delete("api/results/" + id)
            .then(() => {
              Swal.fire("Deleted!", "Your file has been deleted.", "success");
              // Fire.$emit('AfterCreate');
              this.loadUsers();
            })
            .catch((data) => {
              Swal.fire("Failed!", data.message, "warning");
            });
        }
      });
    },

    loadStudents() {
      this.form
        .get("api/student-list")
        .then(({ data }) => {
          this.students = data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    loadResults() {
      this.$Progress.start();
      axios.get("api/results").then(({ data }) => (this.results = data.data));
      this.$Progress.finish();
    },

    createResult() {
      this.form
        .post("api/results")
        .then((response) => {
          $("#addNew").modal("hide");

          Toast.fire({
            icon: "success",
            title: response.data.message,
          });

          this.$Progress.finish();
          this.loadResults();
        })
        .catch(() => {
          Toast.fire({
            icon: "error",
            title: "Some error occured! Please try again",
          });
        });
    },
  },
  mounted() {
    console.log("Result Component mounted.");
  },
  created() {
    this.$Progress.start();
    this.loadResults();
    this.loadStudents();
    this.$Progress.finish();
  },
};
</script>
