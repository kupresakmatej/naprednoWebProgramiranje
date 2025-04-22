var express = require('express');
var mongoose = require('mongoose');
var methodOverride = require('method-override');
var projectRoutes = require('./routes/projects');
var path = require('path');
var logger = require('morgan');

var app = express();

mongoose.connect('mongodb://localhost:27017/projects')
  .then(() => console.log('Povezan s MongoDB'))
  .catch(err => console.error('Greška pri povezivanju:', err));

app.use(logger('dev'));
app.use(express.json());
app.use(express.urlencoded({ extended: true }));
app.use(methodOverride('_method'));
app.use(express.static(path.join(__dirname, 'public')));

app.set('views', path.join(__dirname, 'views'));
app.set('view engine', 'jade');

app.get('/', (req, res) => {
  res.redirect('/projects');
});

app.use('/projects', projectRoutes);

app.use(function(err, req, res, next) {
  res.locals.message = err.message;
  res.locals.error = req.app.get('env') === 'development' ? err : {};
  res.status(err.status || 500);
  res.render('error', { title: 'Error' });
});

module.exports = app;