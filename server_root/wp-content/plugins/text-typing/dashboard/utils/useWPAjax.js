import { useState, useEffect } from 'react';


/**
 * 
 * @param {*} action 
 * @param {*} params 
 * @param {*} set 
 * @returns 
 */
const useWPAjax = (action, params = {}, initialize = true) => {
	const [isLoading, setIsLoading] = useState(false);
	const [isError, setIsError] = useState(false);
	const [error, setError] = useState(null);
	const [data, setData] = useState(null);

	if (!wp.ajax) {
		// eslint-disable-next-line no-console
		console.error('Please use wp-util as a dependency');
		return;
	}

	const sendRequest = (payload = {}) => {
		setIsLoading(true);
		setIsError(false);
		setError(null);
		wp.ajax
			.post(action, { ...params, ...payload })
			.done((res) => {
				setIsLoading(false);
				setData(res);
			})
			.fail((error) => {
				setIsLoading(false);
				setIsError(true);
				setError(error);
			});
	};

	const request = async (payload = {}) => {
		sendRequest(payload);
	};

	useEffect(() => {
		if (initialize) {
			sendRequest(params);
		}
	}, []);

	return { data, saveData: request, refetch: request, isLoading, isError, error };
};
export default useWPAjax;