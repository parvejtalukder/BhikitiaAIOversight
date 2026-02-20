const express = require('express');
const analyze = require('./analyzer');

const app = express();
app.use(express.json({ limit: '5mb' }));

app.post('/analyze', async (req, res) => {
    try {
        const { title, content } = req.body;

        if (!title || !content) {
            return res.status(400).json({ error: "Missing data" });
        }

        const result = await analyze(title, content);

        res.json(result);

    } catch (err) {
        console.error(err);
        res.status(500).json({ error: "Analysis failed" });
    }
});

app.listen(3000, '127.0.0.1', () => {
    console.log("Bhikitia AI Engine running on http://127.0.0.1:3000");
});