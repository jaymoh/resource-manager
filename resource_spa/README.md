# Remote Spa (remote-spa)

Remote Front End SPA with Quasar

## Install the dependencies
```bash
yarn
# or
npm install
```

### Install Quasar CLI globally
```bash
RUN npm install -g @quasar/cli
```

### Create the .env file

```bash
cp .env.production .env
```
Have the following variables in the `.env` file:

```
APP_NAME="ResourceManager"
NODE_ENV=development
LOCAL_API_URL=http://127.0.0.1:8021/api/
PRODUCTION_API_URL=http://127.0.0.1:8021/api/
```

This is the endpoint `http://127.0.0.1:8021/api/` running the laravel API. You are free to change it.

### Start the app in development mode (hot-code reloading, error reporting, etc.)
```bash
npm run dev
```


### Lint the files
```bash
yarn lint
# or
npm run lint
```


### Format the files
```bash
yarn format
# or
npm run format
```



### Build the app for production
```bash
npm run build
```

### Customize the configuration
See [Configuring quasar.config.js](https://v2.quasar.dev/quasar-cli-vite/quasar-config-js).
