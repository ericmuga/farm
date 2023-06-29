export function useLocation() {
    async function getLocation() {
      return new Promise((resolve, reject) => {
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(
            position => {
              const latitude = position.coords.latitude;
              const longitude = position.coords.longitude;
              resolve({ latitude, longitude });
            },
            error => {
              reject('Error retrieving location: ' + error.message);
            }
          );
        } else {
          reject('Geolocation is not supported by this browser.');
        }
      });
    }

    return {
      getLocation
    };
  }
