require('jgrowl/jquery.jgrowl.min');

const notify = {};

/**
 * @param {String} message
 * @param {String} header
 * @return {void}
 */
notify.error = (message, header = 'Error!') => {
  $.jGrowl(message, { header, theme: 'bg-danger' });
};

/**
 * @param {String} message
 * @param {String} header
 * @return {void}
 */
notify.info = (message, header = 'Info') => {
  $.jGrowl(message, { header, theme: 'bg-primary' });
};

/**
 * @param {String} message
 * @param {String} header
 * @return {void}
 */
notify.success = (message, header = 'Success') => {
  $.jGrowl(message, { header, theme: 'bg-success' });
};

/**
 * @param {String} message
 * @param {String} header
 * @return {void}
 */
notify.warning = (message, header = 'Warning!') => {
  $.jGrowl(message, { header, theme: 'bg-warning' });
};

/**
 * @param {*} errors
 * @return {void}
 */
notify.showErrors = (errors) => {
  if (!errors.response) {
    return;
  }

  /**
   * @param {*} item
   * @return {boolean}
   */
  const isString = (item) => typeof item === 'string' || item instanceof String;

  if (isString(errors.response.data)) {
    notify.error(errors.response.data);

    return;
  }

  if (!errors.response.data) {
    return;
  }

  if (errors.response.data.errors) {
    Object.keys(errors.response.data.errors).forEach((key) => {
      for (const error of errors.response.data.errors[key]) {
        notify.error(error);
      }
    });

    return;
  }

  if (errors.response.data.message) {
    if (isString(errors.response.data.message)) {
      notify.error(errors.response.data.message);
    } else {
      Object.keys(errors.response.data.message).forEach((key) => {
        for (const error of errors.response.data.message[key]) {
          notify.error(error);
        }
      });
    }

    return;
  }

  for (const error of errors.response.data) {
    notify.error(error);
  }
};

window.notify = notify;
