/**
* @author safecaches
* This script will build the tonicbloomsPins javascript object that will be use
* inside any script in the page to share the pins
**/

// the global variable
var tonicbloomsPins;


(function( $ ) {
  "use strict";

  tonicbloomsPins = {
    yellow: injectedData.yellow,
    white: injectedData.white,
    pink: injectedData.pink,
    yellowPinsCutOffTime: injectedData.yellowPinsCutOffTime,
    whiteAndPinkPinsCutOffTime: injectedData.whiteAndPinkPinsCutOffTime,
    cutOffTimeSafety: injectedData.cutOffTimeSafety,
    isAValidTonicbloomsPin: function( aPostalCode ) {
      /**
      * @param a postal code
      * @return if this postal code sortation unit is a valid tonicblooms pin
      * @see https://en.wikipedia.org/wiki/Postal_codes_in_Canada
      **/

      return (
        ( this.isAcoloredPin(aPostalCode, this.yellow ) )
        || ( this.isAcoloredPin(aPostalCode, this.pink ) )
        || ( this.isAcoloredPin(aPostalCode, this.white ) )
      );
    },
    substractMinutesFromTime: function( time, minutes ) {
      /**
      * @param a time object ( json with two keys hours and minutes )
      * @param an integer representing the number of minutes to substract to the time
      * @return a time object with the new time
      **/

      time = JSON.parse(time);
      var aDate = new Date();
      
      aDate.setHours( time.hours );
      aDate.setMinutes( time.minutes );
      aDate.setTime( aDate.getTime() - minutes * 60 * 1000 );
      
      return ({
        "hours": aDate.getHours(),
        "minutes": aDate.getMinutes(),
      });
    },
    getYellowPinsCutOffTime: function() {
      return ( this.substractMinutesFromTime( this.yellowPinsCutOffTime, this.cutOffTimeSafety) );
    },
    getWhiteAndPinkPinsCutOffTime: function() {
      return ( this.substractMinutesFromTime( this.whiteAndPinkPinsCutOffTime, this.cutOffTimeSafety) );
    },
    isAcoloredPin: function( aPostalCode, coloredPins ) {
      /**
      * @param a postal code
      * @param an array of pin
      * @return if this postal code sortation unit is in the array of pin
      **/
      
      var forwardSortationUnit = aPostalCode.substring(0, 3);

      forwardSortationUnit = forwardSortationUnit.toUpperCase();
      return ( coloredPins.indexOf(forwardSortationUnit) != -1 ) ;
    },
    isAYellowPin: function( aPostalCode ) {
      return ( this.isAcoloredPin(aPostalCode, this.yellow ) );
    },
    isAPinkPin: function( aPostalCode ) {
      return ( this.isAcoloredPin(aPostalCode, this.pink ) );
    },
    isAWhitePin: function( aPostalCode ) {
      return ( this.isAcoloredPin(aPostalCode, this.white ) );
    },
  }

}(jQuery));




