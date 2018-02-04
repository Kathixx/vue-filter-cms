


## Introduction
The main goal of the project is to create a CMS to filter any kind of entries without using a database. 
Logic and frontend are implemented with VueJS and Axios for the AJAX-Requests. Information are stored in the data.js, which is exporting a JSON with entries and filters. 
Submitted login credentials are encryption with [SHA3-512](https://github.com/emn178/js-sha3).   The backend of the CMS is only accessible with a session created by entering the right username and password provited in the actions.php.

## Installation

1) Clone the repository:
```
git clone moezchr@vue-filter-cms
```
2) Change directotory into the repository.
```
cd vue-filter-cms
```
2) Install the dependencies with npm or yarn:
```
npm install
```

```
yarn install
```

3) Start your apache or nginx server and point it at the root of the cloned repository.
4) Want to make improvments to the code?
```
npm run dev
```
or
```
yarn dev
```

## Features
- Simple front end with the filter options.
- Login with hashed credentials
- Backend with all kind of content management options
	- Adding, deleting and editing of entries.
	- Adding and deleting of filters

## Suggestions or issues
Feel free to leave suggestions or to point out issues.

## Licence
It's customizable and of course open to use for private and commercial use under the [MIT](LICENSE) license.