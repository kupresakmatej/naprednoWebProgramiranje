var express = require('express');
var router = express.Router();
var User = require('../models/user');
var { check, validationResult } = require('express-validator');

router.get('/register', (req, res) => {
  if (req.session.user) {
    return res.redirect('/projects');
  }
  res.render('users/register', { title: 'Registracija', errors: [] });
});

router.post('/register', [
  check('email').isEmail().withMessage('Email nije ispravan'),
  check('password').isLength({ min: 6 }).withMessage('Lozinka mora imati barem 6 znakova'),
  check('name').notEmpty().withMessage('Ime je obavezno')
], async (req, res) => {
  var errors = validationResult(req);
  if (!errors.isEmpty()) {
    return res.render('users/register', { title: 'Registracija', errors: errors.array() });
  }

  try {
    var existingUser = await User.findOne({ email: req.body.email });
    if (existingUser) {
      return res.render('users/register', { title: 'Registracija', errors: [{ msg: 'Email je već u upotrebi' }] });
    }

    var user = new User({
      email: req.body.email,
      password: req.body.password,
      name: req.body.name
    });
    await user.save();
    req.session.user = { id: user._id, email: user.email, name: user.name };
    res.redirect('/projects');
  } catch (err) {
    console.error('Greška pri registraciji:', err);
    res.render('users/register', { title: 'Registracija', errors: [{ msg: 'Greška pri registraciji' }] });
  }
});

router.get('/login', (req, res) => {
  if (req.session.user) {
    return res.redirect('/projects');
  }
  res.render('users/login', { title: 'Prijava', errors: [] });
});

router.post('/login', [
  check('email').isEmail().withMessage('Email nije ispravan'),
  check('password').notEmpty().withMessage('Lozinka je obavezna')
], async (req, res) => {
  var errors = validationResult(req);
  if (!errors.isEmpty()) {
    return res.render('users/login', { title: 'Prijava', errors: errors.array() });
  }

  try {
    var user = await User.findOne({ email: req.body.email });
    if (!user) {
      return res.render('users/login', { title: 'Prijava', errors: [{ msg: 'Email nije pronađen' }] });
    }

    var isMatch = await user.comparePassword(req.body.password);
    if (!isMatch) {
      return res.render('users/login', { title: 'Prijava', errors: [{ msg: 'Pogrešna lozinka' }] });
    }

    req.session.user = { id: user._id, email: user.email, name: user.name };
    res.redirect('/projects');
  } catch (err) {
    console.error('Greška pri prijavi:', err);
    res.render('users/login', { title: 'Prijava', errors: [{ msg: 'Greška pri prijavi' }] });
  }
});

router.get('/logout', (req, res) => {
  req.session.destroy((err) => {
    if (err) {
      console.error('Greška pri odjavi:', err);
    }
    res.redirect('/users/login');
  });
});

module.exports = router;