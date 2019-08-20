import { GET_WORKOUTS, ADD_WORKOUT, DELETE_WORKOUT, SET_WORKOUTS } from '../actions/types';

const initialState = {
  workouts: [
    {
      name: 'Pushups',
      calories: -345,
    },
    {
      name: 'Mile Run',
      calories: -675,
    },
  ],
};

const workoutReducer = (state = initialState, action) => {
  switch (action.type) {
    case GET_WORKOUTS:
      return {
        ...state,
        workouts: state.workouts,
      };
    case SET_WORKOUTS:
      return {
        ...state,
        workouts: action.payload,
      };
    case ADD_WORKOUT:
      return {
        ...state,
        workouts: [...state.workouts, action.payload],
      };
    case DELETE_WORKOUT:
      return {
        ...state,
        workouts: state.filter(workouts => workouts.id !== action.payload),
      };
    default:
      return state.workouts;
  }
};

export default workoutReducer;