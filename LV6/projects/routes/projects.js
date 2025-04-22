var express = require('express');
var router = express.Router();
var Project = require('../models/project');
var TeamMember = require('../models/teamMember');
var { check, validationResult } = require('express-validator');

router.get('/', async (req, res) => {
  var projects = await Project.find().populate('teamMembers');
  res.render('projects/index', { title: 'Projekti', projects: projects });
});

router.get('/new', (req, res) => {
  res.render('projects/new', { title: 'Novi projekt', errors: [] });
});

router.post('/', [
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
      endDate: req.body.endDate
    });
    await project.save();
    console.log('Projekt spremljen:', project);
    res.redirect('/projects');
  } catch (err) {
    console.error('Greška pri spremanju projekta:', err);
    res.render('projects/new', { title: 'Novi projekt', errors: [{ msg: 'Greška pri spremanju projekta' }] });
  }
});

router.get('/', async (req, res) => {
  try {
    var projects = await Project.find().populate('teamMembers');
    console.log('Dohvaćeni projekti:', projects);
    res.render('projects/index', { title: 'Projekti', projects: projects });
  } catch (err) {
    console.error('Greška pri dohvaćanju projekata:', err);
    res.render('projects/index', { title: 'Projekti', projects: [] });
  }
});

router.get('/:id', async (req, res) => {
  var project = await Project.findById(req.params.id).populate('teamMembers');
  res.render('projects/show', { title: project.name, project: project });
});

router.get('/:id/edit', async (req, res) => {
  var project = await Project.findById(req.params.id);
  res.render('projects/edit', { title: 'Uredi projekt', project: project, errors: [] });
});

router.put('/:id', [
  check('name').notEmpty().withMessage('Naziv je obavezan'),
  check('description').notEmpty().withMessage('Opis je obavezan'),
  check('price').isNumeric().withMessage('Cijena mora biti broj')
], async (req, res) => {
  var errors = validationResult(req);
  if (!errors.isEmpty()) {
    var project = await Project.findById(req.params.id);
    return res.render('projects/edit', { title: 'Uredi projekt', project: project, errors: errors.array() });
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
});

router.delete('/:id', async (req, res) => {
  await Project.findByIdAndDelete(req.params.id);
  res.redirect('/projects');
});

router.get('/:id/team/new', async (req, res) => {
  var project = await Project.findById(req.params.id);
  res.render('projects/addMember', { title: 'Dodaj člana tima', project: project, errors: [] });
});

router.post('/:id/team', [
  check('name').notEmpty().withMessage('Ime je obavezno'),
  check('role').notEmpty().withMessage('Uloga je obavezna')
], async (req, res) => {
  var errors = validationResult(req);
  if (!errors.isEmpty()) {
    var project = await Project.findById(req.params.id);
    return res.render('projects/addMember', { title: 'Dodaj člana tima', project: project, errors: errors.array() });
  }
  var teamMember = new TeamMember({
    name: req.body.name,
    role: req.body.role
  });
  await teamMember.save();
  var project = await Project.findById(req.params.id);
  project.teamMembers.push(teamMember._id);
  await project.save();
  res.redirect(`/projects/${req.params.id}`);
});

router.delete('/:projectId/team/:memberId', async (req, res) => {
  try {
    const project = await Project.findById(req.params.projectId);
    if (!project) {
      return res.status(404).send('Projekt nije pronađen');
    }
    
    project.teamMembers = project.teamMembers.filter(
      member => member.toString() !== req.params.memberId
    );
    await project.save();
    
    await TeamMember.findByIdAndDelete(req.params.memberId);
    
    res.redirect(`/projects/${req.params.projectId}`);
  } catch (err) {
    console.error('Greška pri brisanju člana tima:', err);
    res.status(500).send('Greška pri brisanju člana tima');
  }
});

module.exports = router;