var express = require('express');
var router = express.Router();
var Project = require('../models/project');
var User = require('../models/user');
var { check, validationResult } = require('express-validator');

const ensureAuthenticated = (req, res, next) => {
  if (!req.session.user) {
    return res.redirect('/users/login');
  }
  next();
};

router.get('/', ensureAuthenticated, async (req, res) => {
  try {
    var projects = await Project.find({ isArchived: false }).populate('teamMembers').populate('manager');
    console.log('Dohvaćeni projekti:', projects);
    res.render('projects/index', { title: 'Projekti', projects: projects });
  } catch (err) {
    console.error('Greška pri dohvaćanju projekata:', err);
    res.render('projects/index', { title: 'Projekti', projects: [] });
  }
});

router.get('/my-managed', ensureAuthenticated, async (req, res) => {
  try {
    var projects = await Project.find({ manager: req.session.user.id, isArchived: false })
      .populate('teamMembers')
      .populate('manager');
    res.render('projects/myManaged', { title: 'Moji projekti (voditelj)', projects: projects });
  } catch (err) {
    console.error('Greška pri dohvaćanju projekata:', err);
    res.render('projects/myManaged', { title: 'Moji projekti (voditelj)', projects: [] });
  }
});

router.get('/my-membership', ensureAuthenticated, async (req, res) => {
  try {
    var projects = await Project.find({ teamMembers: req.session.user.id, isArchived: false })
      .populate('teamMembers')
      .populate('manager');
    res.render('projects/myMembership', { title: 'Moji projekti (član)', projects: projects });
  } catch (err) {
    console.error('Greška pri dohvaćanju projekata:', err);
    res.render('projects/myMembership', { title: 'Moji projekti (član)', projects: [] });
  }
});

router.get('/archive', ensureAuthenticated, async (req, res) => {
  try {
    var projects = await Project.find({
      isArchived: true,
      $or: [
        { manager: req.session.user.id },
        { teamMembers: req.session.user.id }
      ]
    }).populate('teamMembers').populate('manager');
    res.render('projects/archive', { title: 'Arhiva projekata', projects: projects });
  } catch (err) {
    console.error('Greška pri dohvaćanju arhiviranih projekata:', err);
    res.render('projects/archive', { title: 'Arhiva projekata', projects: [] });
  }
});

router.get('/new', ensureAuthenticated, (req, res) => {
  res.render('projects/new', { title: 'Novi projekt', errors: [] });
});

router.post('/', ensureAuthenticated, [
  check('name').notEmpty().withMessage('Naziv je obavezan'),
  check('description').notEmpty().withMessage('Opis je obavezan'),
  check('price').isNumeric().withMessage('Cijena mora biti broj'),
  check('startDate').notEmpty().withMessage('Datum početka je obavezan'),
  check('endDate').notEmpty().withMessage('Datum završetka je obavezan')
], async (req, res) => {
  var errors = validationResult(req);
  if (!errors.isEmpty()) {
    return res.render('projects/new', { title: 'Novi projekt', errors: errors.array() });
  }
  try {
    var project = new Project({
      name: req.body.name,
      description: req.body.description,
      price: req.body.price,
      tasks: req.body.tasks ? req.body.tasks.split(',').map(task => task.trim()) : [],
      startDate: req.body.startDate,
      endDate: req.body.endDate,
      manager: req.session.user.id,
      teamMembers: [req.session.user.id]
    });
    await project.save();
    console.log('Projekt spremljen:', project);
    res.redirect('/projects');
  } catch (err) {
    console.error('Greška pri spremanju projekta:', err);
    res.render('projects/new', { title: 'Novi projekt', errors: [{ msg: 'Greška pri spremanju projekta' }] });
  }
});

router.get('/:id', ensureAuthenticated, async (req, res) => {
  try {
    var project = await Project.findById(req.params.id).populate('teamMembers').populate('manager');
    if (!project) {
      return res.status(404).render('error', { message: 'Projekt nije pronađen', error: {} });
    }
    res.render('projects/show', { title: project.name, project: project });
  } catch (err) {
    console.error('Greška pri dohvaćanju projekta:', err);
    res.status(500).render('error', { message: 'Greška pri dohvaćanju projekta', error: err });
  }
});

router.get('/:id/edit', ensureAuthenticated, async (req, res) => {
  try {
    var project = await Project.findById(req.params.id);
    if (!project) {
      return res.status(404).render('error', { message: 'Projekt nije pronađen', error: {} });
    }
    if (project.manager.toString() !== req.session.user.id) {
      return res.status(403).render('error', { message: 'Samo voditelj projekta može uređivati projekt', error: {} });
    }
    res.render('projects/edit', { title: 'Uredi projekt', project: project, errors: [] });
  } catch (err) {
    console.error('Greška pri dohvaćanju projekta:', err);
    res.status(500).render('error', { message: 'Greška pri dohvaćanju projekta', error: err });
  }
});

router.put('/:id', ensureAuthenticated, [
  check('name').notEmpty().withMessage('Naziv je obavezan'),
  check('description').notEmpty().withMessage('Opis je obavezan'),
  check('price').isNumeric().withMessage('Cijena mora biti broj')
], async (req, res) => {
  var errors = validationResult(req);
  if (!errors.isEmpty()) {
    var project = await Project.findById(req.params.id);
    return res.render('projects/edit', { title: 'Uredi projekt', project: project, errors: errors.array() });
  }
  try {
    var project = await Project.findById(req.params.id);
    if (project.manager.toString() !== req.session.user.id) {
      return res.status(403).render('error', { message: 'Samo voditelj projekta može uređivati projekt', error: {} });
    }
    await Project.findByIdAndUpdate(req.params.id, {
      name: req.body.name,
      description: req.body.description,
      price: req.body.price,
      tasks: req.body.tasks ? req.body.tasks.split(',').map(task => task.trim()) : [],
      startDate: req.body.startDate,
      endDate: req.body.endDate
    });
    res.redirect(`/projects/${req.params.id}`);
  } catch (err) {
    console.error('Greška pri ažuriranju projekta:', err);
    res.status(500).render('error', { message: 'Greška pri ažuriranju projekta', error: err });
  }
});

router.delete('/:id', ensureAuthenticated, async (req, res) => {
  try {
    var project = await Project.findById(req.params.id);
    if (project.manager.toString() !== req.session.user.id) {
      return res.status(403).render('error', { message: 'Samo voditelj projekta može obrisati projekt', error: {} });
    }
    await Project.findByIdAndDelete(req.params.id);
    res.redirect('/projects');
  } catch (err) {
    console.error('Greška pri brisanju projekta:', err);
    res.status(500).render('error', { message: 'Greška pri brisanju projekta', error: err });
  }
});

router.get('/:id/team/new', ensureAuthenticated, async (req, res) => {
  try {
    var project = await Project.findById(req.params.id);
    if (project.manager.toString() !== req.session.user.id) {
      return res.status(403).render('error', { message: 'Samo voditelj projekta može dodavati članove tima', error: {} });
    }
    var users = await User.find();
    res.render('projects/addMember', { title: 'Dodaj člana tima', project: project, users: users, errors: [] });
  } catch (err) {
    console.error('Greška pri dohvaćanju podataka:', err);
    res.status(500).render('error', { message: 'Greška pri dohvaćanju podataka', error: err });
  }
});

router.post('/:id/team', ensureAuthenticated, [
  check('userId').notEmpty().withMessage('Korisnik je obavezan')
], async (req, res) => {
  var errors = validationResult(req);
  if (!errors.isEmpty()) {
    var project = await Project.findById(req.params.id);
    var users = await User.find();
    return res.render('projects/addMember', { title: 'Dodaj člana tima', project: project, users: users, errors: errors.array() });
  }
  try {
    var project = await Project.findById(req.params.id);
    if (project.manager.toString() !== req.session.user.id) {
      return res.status(403).render('error', { message: 'Samo voditelj projekta može dodavati članove tima', error: {} });
    }
    var userId = req.body.userId;
    if (!project.teamMembers.includes(userId)) {
      project.teamMembers.push(userId);
      await project.save();
    }
    res.redirect(`/projects/${req.params.id}`);
  } catch (err) {
    console.error('Greška pri dodavanju člana tima:', err);
    res.status(500).render('error', { message: 'Greška pri dodavanju člana tima', error: err });
  }
});

router.delete('/:projectId/team/:memberId', ensureAuthenticated, async (req, res) => {
  try {
    const project = await Project.findById(req.params.projectId);
    if (project.manager.toString() !== req.session.user.id) {
      return res.status(403).render('error', { message: 'Samo voditelj projekta može uklanjati članove tima', error: {} });
    }
    project.teamMembers = project.teamMembers.filter(
      member => member.toString() !== req.params.memberId
    );
    await project.save();
    res.redirect(`/projects/${req.params.projectId}`);
  } catch (err) {
    console.error('Greška pri brisanju člana tima:', err);
    res.status(500).render('error', { message: 'Greška pri brisanju člana tima', error: err });
  }
});

router.post('/:id/archive', ensureAuthenticated, async (req, res) => {
  try {
    var project = await Project.findById(req.params.id);
    if (project.manager.toString() !== req.session.user.id) {
      return res.status(403).render('error', { message: 'Samo voditelj projekta može arhivirati projekt', error: {} });
    }
    await Project.findByIdAndUpdate(req.params.id, { isArchived: true });
    res.redirect('/projects');
  } catch (err) {
    console.error('Greška pri arhiviranju projekta:', err);
    res.status(500).render('error', { message: 'Greška pri arhiviranju projekta', error: err });
  }
});

router.get('/:id/edit-tasks', ensureAuthenticated, async (req, res) => {
  try {
    var project = await Project.findById(req.params.id).populate('teamMembers');
    if (!project.teamMembers.some(member => member._id.toString() === req.session.user.id)) {
      return res.status(403).render('error', { message: 'Samo članovi tima mogu uređivati obavljene poslove', error: {} });
    }
    res.render('projects/editTasks', { title: 'Uredi obavljene poslove', project: project, errors: [] });
  } catch (err) {
    console.error('Greška pri dohvaćanju projekta:', err);
    res.status(500).render('error', { message: 'Greška pri dohvaćanju projekta', error: err });
  }
});

router.put('/:id/edit-tasks', ensureAuthenticated, async (req, res) => {
  try {
    var project = await Project.findById(req.params.id).populate('teamMembers');
    if (!project.teamMembers.some(member => member._id.toString() === req.session.user.id)) {
      return res.status(403).render('error', { message: 'Samo članovi tima mogu uređivati obavljene poslove', error: {} });
    }
    await Project.findByIdAndUpdate(req.params.id, {
      tasks: req.body.tasks ? req.body.tasks.split(',').map(task => task.trim()) : []
    });
    res.redirect(`/projects/${req.params.id}`);
  } catch (err) {
    console.error('Greška pri ažuriranju obavljenih poslova:', err);
    res.render('projects/editTasks', { title: 'Uredi obavljene poslove', project: project, errors: [{ msg: 'Greška pri ažuriranju obavljenih poslova' }] });
  }
});

module.exports = router;