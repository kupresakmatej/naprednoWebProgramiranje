var mongoose = require('mongoose');

var projectSchema = new mongoose.Schema({
  name: { type: String, required: true },
  description: { type: String, required: true },
  price: { type: Number, required: true },
  tasks: [{ type: String }],
  startDate: { type: Date, required: true },
  endDate: { type: Date, required: true },
  teamMembers: [{ type: mongoose.Schema.Types.ObjectId, ref: 'TeamMember' }]
});

module.exports = mongoose.model('Project', projectSchema);