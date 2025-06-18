const express = require('express');
const router = express.Router();
const covidController = require('../controllers/covidController');

router.get('/', covidController.getAllCases);
router.get('/:hos_id', covidController.getCaseByHospital);
router.get('/stats/totalgender', covidController.totalgender);

module.exports = router;
