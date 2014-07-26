/** @jsx React.DOM */
var React        = require('react/addons');
var optionsStore = require('../app.js').optionsStore;
var str          = require('underscore.string');

var OptionsForm = React.createClass({
  mixins: [React.addons.LinkedStateMixin],

  getInitialState: function() {
    return this.props.options;
  },

  handleSubmit: function(event) {
    event.preventDefault();
    this.props.noticeChange('progress', 'Saving settings ...');

    // TODO: Is there a better way to do this?
    var state            = this.state;
    state.scrollSpeed    = parseInt(state.scrollSpeed, 10);
    state.scrollDistance = parseInt(state.scrollDistance, 10);

    optionsStore.save(this.state)
      .then(this.updateState)
      .catch(this.showError);
  },

  handleReset: function(event) {
    event.preventDefault();
    var confirmed = confirm('Restore Defaults: Are you sure?');
    if (!confirmed) return;

    this.props.noticeChange('progress', 'Restoring defaults ...');

    optionsStore.reset()
      .then(this.updateState)
      .catch(this.showError);
  },

  updateState: function() {
    this.setState(optionsStore.getOptions());
    this.props.noticeChange('success', 'Settings saved successfully.');
  },

  showError: function(error) {
    this.props.noticeChange('error', error);
  },

  renderSelect: function(field, values, property) {
    return (
      <select id={field} name={field} valueLink={this.linkState(property)}>
        {this.renderSelectOptions(values)}
      </select>
    );
  },

  renderSelectOptions: function(values) {
    return values.map(function(value, index) {
      return (
        <option
          key={index}
          value={value} >{str.capitalize(value)}</option>
      );
    });
  },

  render: function() {
    return (
      <form onSubmit={this.handleSubmit}>
        <table className="form-table">
          <tbody>
            <tr>
              <th scope="row">
                <label htmlFor="style">Style</label>
              </th>
              <td>
                {this.renderSelect('style', this.props.styleTypes, 'style')}
              </td>
            </tr>
            <tr>
              <th scope="row">
                <label htmlFor="scrollText">Scroll Text</label>
              </th>
              <td>
                <input name="scrollText" id="scrollText" valueLink={this.linkState('scrollText')} className="regular-text" type="text" />
              </td>
            </tr>
            <tr>
              <th scope="row">
                <label htmlFor="scrollDistance">Scroll Distance (pixels)</label>
              </th>
              <td>
                <input name="scrollDistance" id="scrollDistance" valueLink={this.linkState('scrollDistance')} className="regular-text" type="number" />
              </td>
            </tr>
            <tr>
              <th scope="row">
                <label htmlFor="scrollSpeed">Scroll Speed (ms)</label>
              </th>
              <td>
                <input name="scrollSpeed" id="scrollSpeed" valueLink={this.linkState('scrollSpeed')} className="regular-text" type="number" />
              </td>
            </tr>
            <tr>
              <th scope="row">
                <label htmlFor="animation">Animation</label>
              </th>
              <td>
                {this.renderSelect('animation', this.props.animationTypes, 'animation')}
              </td>
            </tr>
          </tbody>
        </table>
        <p className="submit">
          <input name="submit" className="button button-primary" value="Save Changes" type="submit" />
          &nbsp;
          <input name="reset" className="button" value="Restore Defaults" type="submit" onClick={this.handleReset} />
        </p>
      </form>
    );
  }
});

module.exports = OptionsForm;
