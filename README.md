testRepo
========

Reactjs Event calender using Material Desgin (MaterialUI) [material-ui](http://www.material-ui.com/#/)

INSTALLATION
------------

 * npm install react-material-calender [npm](https://www.npmjs.com/package/react-material-calender)
	

CONFIGURATION
-------------
```
	var React = require('react');
	var ReactDOM = require('react-dom');
	var Calendar = require("react-material-calender");

	var events = [{
			"type": "Published",
			"start": "2016-01-16T11:00:00.000Z",
			"title": "Meeting",
			"content": "Board meeting"
			}, {
			"type": "Activated",
			"start": "2016-01-17T12:00:00.000Z",
			"title": "Interview",
			"content": "with job seeker"
		}];

	
	ReactDOM.render(<Calendar data={events}/>,document.getElementById('main'));
```


Dev-dependencies
------------

 * Browserify
 * reactify
