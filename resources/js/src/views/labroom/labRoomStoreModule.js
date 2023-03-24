import axios from "@axios";

export default {
  namespaced: true,
  getters: {},
  actions: {
    fetchLabRooms(ctx, queryParams) {
      return new Promise((resolve, reject) => {
        axios
          .get("/lab-room", { params: queryParams })
          .then((response) => resolve(response))
          .catch((error) => reject(error));
      });
    },
    fetchLabRoom(ctx, { id }) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/lab-room/${id}`)
          .then((response) => resolve(response))
          .catch((error) => reject(error));
      });
    },
    addLabRoom(ctx, dataSend) {
      var form_data = new FormData();

      for (var key in dataSend) {
        form_data.append(key, dataSend[key]);
      }

      return new Promise((resolve, reject) => {
        axios
          .post("/lab-room", form_data, {
            headers: {
              "content-type": "multipart/form-data",
            },
          })
          .then((response) => {
            return resolve(response);
          })
          .catch((error) => reject(error));
      });
    },

    editLabRoom(ctx, dataSend) {
      var form_data = new FormData();

      for (var key in dataSend) {
        form_data.append(key, dataSend[key]);
      }

      form_data.append("_method", "PUT");

      return new Promise((resolve, reject) => {
        axios
          .post(`/lab-room/${dataSend.id}`, form_data, {
            headers: {
              "content-type": "multipart/form-data",
            },
          })
          .then((response) => {
            return resolve(response);
          })
          .catch((error) => reject(error));
      });
    },

    deleteLabRoom(ctx, { id }) {
      return new Promise((resolve, reject) => {
        axios
          .delete(`/lab-room/${id}`)
          .then((response) => {
            return resolve(response);
          })
          .catch((error) => reject(error));
      });
    },

    editLevelLabRoom(ctx, dataSend) {
        return new Promise((resolve, reject) => {
          axios
            .put(`/lab-room/level/${dataSend.id}`, dataSend)
            .then((response) => {
              return resolve(response);
            })
            .catch((error) => reject(error));
        });
      },

    
  },
};
