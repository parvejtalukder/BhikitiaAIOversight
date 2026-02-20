const compromise = require('compromise');

module.exports = async function(title, text) {

    const doc = compromise(text);

    const sentences = doc.sentences().out('array');
    const summary = sentences.slice(0, 2).join(' ') || "No summary available";

    const people = doc.people().out('array');
    const dates = doc.dates().out('array');
    const places = doc.places().out('array');

    let keyFacts = [];

    if (people.length) keyFacts.push("People: " + people.join(', '));
    if (dates.length) keyFacts.push("Dates: " + dates.join(', '));
    if (places.length) keyFacts.push("Places: " + places.join(', '));

    const confidence = Math.min(95, 50 + keyFacts.length * 10);

    return {
        title,
        summary,
        keyFacts: keyFacts.join(' | ') || "No key facts found",
        confidence,
        date: new Date().toISOString().split('T')[0]
    };
};