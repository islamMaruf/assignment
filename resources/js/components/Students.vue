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
          <select
            name="department"
            v-model="search.department"
            id="department"
            class="form-control"
          >
            <option value="">Select Department</option>
            <option
              v-for="dept in departments"
              :key="dept.id"
              :value="dept.name"
            >
              {{ dept.name.toUpperCase() }}
            </option>
          </select>
        </div>
        <div class="col-sm-3">
          <div class="form-group">
            <select
              name="gpa"
              v-model="search.gpa"
              id="gpa"
              class="form-control"
            >
              <option value="">Select GPA</option>
              <option
                v-for="result in results"
                :key="result.id"
                :value="result.gpa"
              >
                {{ result.gpa.toUpperCase() }}
              </option>
            </select>
          </div>
        </div>
        <div class="col-sm-3">
          <button class="btn btn-sm btn-primary" @click="searchStudents()">
            Search
          </button>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Student List</h3>

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
                    <th>Name</th>
                    <th>Department</th>
                    <th>Batch No</th>
                    <th>GPA</th>
                    <th>Created</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(student, key) in students.data" :key="student.id">
                    <td>{{ key + 1 }}</td>
                    <td class="text-capitalize">{{ student.name }}</td>
                    <td class="text-capitalize">
                      {{ student.department.name }}
                    </td>
                    <td>{{ student.batch_name }}</td>
                    <td>{{ student.result.gpa ? student.result.gpa : "" }}</td>
                    <td>{{ student.created_at }}</td>

                    <td>
                      <a href="#" @click="editModal(student)">
                        <i class="fa fa-edit blue"></i>
                      </a>
                      /
                      <a href="#" @click="deleteStudent(student.id)">
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
                :data="students"
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
              <h5 class="modal-title" v-show="!editmode">Create New Student</h5>
              <h5 class="modal-title" v-show="editmode">
                Update Student's Info
              </h5>
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

            <form
              @submit.prevent="editmode ? updateStudent() : createStudent()"
            >
              <div class="modal-body">
                <div class="form-group">
                  <label>Name</label>
                  <input
                    v-model="form.name"
                    type="text"
                    name="name"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.has('name') }"
                  />
                  <has-error :form="form" field="name"></has-error>
                </div>
                <div class="form-group">
                  <label>Department</label>
                  <select
                    name="department"
                    v-model="form.department"
                    id="department"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.has('department') }"
                  >
                    <option value="">Select Department</option>
                    <option
                      v-for="dept in departments"
                      :key="dept.id"
                      :value="dept.id"
                    >
                      {{ dept.name.toUpperCase() }}
                    </option>
                  </select>
                  <has-error :form="form" field="department"></has-error>
                </div>
                <div class="form-group">
                  <label>Batch</label>
                  <input
                    v-model="form.batch_name"
                    type="text"
                    name="batch_no"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.has('batch_name') }"
                  />
                  <has-error :form="form" field="batch_name"></has-error>
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
      search: {
        name: "",
        department: "",
        gpa: "",
      },
      editmode: false,
      students: {},
      departments: [],
      results: [],
      form: new Form({
        id: "",
        name: "",
        department: "",
        batch_name: "",
      }),
    };
  },
  methods: {
    searchStudents() {
      const { name, department, gpa } = this.search;

      axios
        .get("api/students", {
          params: {
            name: name,
            department: department,
            gpa: gpa,
          },
        })
        .then(({ data }) => {
          this.students = data.data;
        });
    },
    getResults(page = 1) {
      this.$Progress.start();

      axios
        .get("api/students?page=" + page)
        .then(({ data }) => (this.students = data.data));

      this.$Progress.finish();
    },
    updateStudent() {
      this.$Progress.start();
      // console.log('Editing data');
      this.form
        .put("api/students/" + this.form.id)
        .then((response) => {
          // success
          $("#addNew").modal("hide");
          Toast.fire({
            icon: "success",
            title: response.data.message,
          });
          this.$Progress.finish();
          //  Fire.$emit('AfterCreate');

          this.loadStudents();
        })
        .catch(() => {
          this.$Progress.fail();
        });
    },
    editModal(student) {
      this.editmode = true;
      this.form.reset();
      $("#addNew").modal("show");
      this.form.fill(student);
      this.form.department = student.department.id;
    },
    newModal() {
      this.editmode = false;
      this.form.reset();
      $("#addNew").modal("show");
    },
    deleteStudent(id) {
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
            .delete("api/students/" + id)
            .then(() => {
              Swal.fire("Deleted!", "Your file has been deleted.", "success");
              // Fire.$emit('AfterCreate');
              this.loadStudents();
            })
            .catch((data) => {
              Swal.fire("Failed!", data.message, "warning");
            });
        }
      });
    },
    loadStudents() {
      this.$Progress.start();
      axios.get("api/students").then(({ data }) => {
        console.log(data);
        this.students = data.data;
      });
      this.$Progress.finish();
    },
    loadDepartments() {
      this.form
        .get("api/department-list")
        .then(({ data }) => {
          this.departments = data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    loadResults() {
      this.form
        .get("api/result-gpa-list")
        .then(({ data }) => {
          console.log(data);
          this.results = data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    createStudent() {
      this.form
        .post("api/students")
        .then((response) => {
          $("#addNew").modal("hide");

          Toast.fire({
            icon: "success",
            title: response.data.message,
          });

          this.$Progress.finish();
          this.loadStudents();
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
    console.log("Student Component mounted.");
  },
  created() {
    this.$Progress.start();
    this.loadStudents();
    this.loadDepartments();
    this.loadResults();
    this.$Progress.finish();
  },
};
</script>
