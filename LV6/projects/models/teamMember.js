var mongoose = require('mongoose');

var teamMemberSchema = new mongoose.Schema({
  name: { type: String, required: true },
  role: { type: String, required: true }
});

module.exports = mongoose.model('TeamMember', teamMemberSchema);