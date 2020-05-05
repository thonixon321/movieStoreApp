
import axios from 'axios'

export const axiosHandler = {
  methods: {
    sendAxios(payLoadObj, settingsObj) {
      let axiosUrl = settingsObj.url,
          axiosMethod = settingsObj.method,
          params = {},
          data = {},
          headerObj = {
            'Content-Type': 'application/x-www-form-urlencoded'
          };

      if (axiosMethod == 'GET') {
        params = payLoadObj;
      }else {
        data = payLoadObj;
      }

      //send request
      axios({
        method: axiosMethod,
        url: axiosUrl,
        headers: headerObj,
        data: data,
        params: params
      })
      .then(
        result => settingsObj.callBack(result)
      )
      .catch(
        error => console.log(error)
      )
    }
  }
}



