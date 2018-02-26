import React from 'react'
import ReactDOM from 'react-dom'



class MyForm extends React.Component {
  constructor(props, context) {
    super(props, context);
    this.state = {
      value: ""

    };

    this.handleChange = this.handleChange.bind(this);
    this.handleSubmit = this.handleSubmit.bind(this);

  }


  handleChange(event) {
    this.setState({value: event.target.value});
  }

  handleSubmit(event) {
    //alert('calculate: ' + this.state.value);
    console.log(this.state.value);
    event.preventDefault();
    //this.context.router.replaceWith('/home');

    window.location = 'http://localhost:8000/eval/'+this.state.value;

  }



  render() {
    return (
      <form>
        <label>
          Calculate:
          <input
            type="string"
            value={this.state.value}
            onChange={this.handleChange} />
        </label>
         <button onClick={this.handleSubmit} className="button"> OK </button>
      </form>
    );
  }
}

ReactDOM.render(
  <MyForm />,
  document.getElementById('app')
);
